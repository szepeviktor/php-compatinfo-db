<?php declare(strict_types=1);

/**
 * Unit tests for PHP_CompatInfo_Db, Generic extension base class.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @author     Remi Collet <Remi@FamilleCollet.com>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Tests\Reference;

use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderInterface;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderTrait;
use Bartlett\CompatInfoDb\Domain\Factory\LibraryVersionProviderTrait;
use Bartlett\CompatInfoDb\ExtensionFactory;
use Bartlett\CompatInfoDb\ReferenceInterface;

use Composer\Semver\Semver;

use PHPUnit\Framework\ExpectationFailedException;

use Generator;
use ReflectionClass;
use ReflectionException;
use ReflectionExtension;
use ReflectionFunction;
use ReflectionMethod;
use function interface_exists;

/**
 * @since Release 3.0.0RC1 of PHP_CompatInfo
 * @since Release 1.0.0alpha1 of PHP_CompatInfo_Db
 */
abstract class GenericTest extends TestCase implements ExtensionVersionProviderInterface
{
    protected static $obj = null;

    // Could be defined in Reference but missing (system dependant)
    protected static $optionalreleases    = [];
    protected static $optionalcfgs        = [];
    protected static $optionalconstants   = [];
    protected static $optionalfunctions   = [];
    protected static $optionalclasses     = [];
    protected static $optionalinterfaces  = [];
    protected static $optionalmethods     = [];

    // Could be present but missing in Reference (alias, ...)
    protected static $ignoredcfgs          = [];
    protected static $ignoredconstants     = [];
    protected static $ignoredfunctions     = [];
    protected static $ignoredclasses       = [];
    protected static $ignoredinterfaces    = [];
    protected static $ignoredmethods       = [];
    protected static $ignoredconsts        = [];

    use LibraryVersionProviderTrait;
    use ExtensionVersionProviderTrait;

    /**
     * Sets up the shared fixture.
     *
     * @return void
     * @link   http://phpunit.de/manual/current/en/fixtures.html#fixtures.sharing-fixture
     */
    public static function setUpBeforeClass(): void
    {
        self::$optionalreleases = [];

        $parts = explode('\\', get_called_class());
        $name = strtolower(
            str_replace('ExtensionTest', '', end($parts))
        );
        self::$obj = new ExtensionFactory($name);

        $currentVersion = self::$obj->getCurrentVersion();
        if ($currentVersion === false) {
            // extension did not provide any version information
            return;
        }

        $releases = array_keys(self::$obj->getReleases());

        // platform dependant
        foreach ($releases as $rel_version) {
            if (version_compare($currentVersion, $rel_version, 'lt')) {
                array_push(self::$optionalreleases, $rel_version);
            }
        }
    }

    public static function tearDownAfterClass(): void
    {
        self::$optionalreleases   = [];
        self::$optionalcfgs       = [];
        self::$optionalconstants  = [];
        self::$optionalfunctions  = [];
        self::$optionalclasses    = [];
        self::$optionalinterfaces = [];
        self::$optionalmethods    = [];

        self::$ignoredcfgs        = [];
        self::$ignoredconstants   = [];
        self::$ignoredfunctions   = [];
        self::$ignoredclasses     = [];
        self::$ignoredinterfaces  = [];
        self::$ignoredmethods     = [];
        self::$ignoredconsts      = [];
    }

    protected function setUp(): void
    {
        $name = self::$obj->getName();
        // special case(s)
        if ('opcache' === $name) {
            $name = 'zend opcache';
        }

        if (!extension_loaded($name)) {
            $this->markTestSkipped(
                sprintf('Extension %s is required.', $name)
            );
        }
    }

    /**
     * Generic Reference validator and producer
     *
     * @param array $elements
     * @param string $opt
     * @return Generator
     */
    private function provideReferenceValues(array $elements, string $opt)
    {
        $extVersion = $this->getExtensionVersion(self::$obj->getName());

        foreach ($elements as $name => $range) {
            $range['ext.min'] = $extVersion;

            if (!empty($range['optional'])) {
                self::${$opt}[] = $name;
                continue;
            }

            $libs = [];
            foreach ($range as $key => $val) {
                if (strpos($key, 'lib_') === 0) {
                    if (!empty($val)) {
                        $libs[$key] = $val;
                    }
                }
            }

            foreach($libs as $lib => $constraint) {
                $lib = str_replace('lib_', '', $lib);
                $ver = $this->getPrettyVersion($lib);

                if (!Semver::satisfies($ver, $constraint)) {
                    self::${$opt}[] = $name;
                    continue 2;
                }
            }
            yield [$name, $range];
        }
    }

    protected static function toText($number)
    {
        $hex = dechex(($number & ~ 15) / 16);

        if (strlen($hex) % 2 !== 0) {
            $hex = '0' . $hex;
        }

        $arr = str_split($hex, 2);

        return implode('.', array_map('hexdec', $arr));
    }

    protected static function toNumber($text)
    {
        $arr = explode('.', $text);
        $arr = array_map('dechex', $arr);
        $hex = '';

        foreach ($arr as $digit) {
            if (strlen($digit) % 2 !== 0) {
                $hex .= '0';
            }
            $hex .= $digit;
        }
        $hex .= 'F';

        return hexdec($hex);
    }

    /**
     * @param string $element
     * @param array $range
     * @param array $optional
     * @param array $ignored
     * @return bool|null NULL if reference should be skipped, boolean otherwise
     */
    private function checkValuesFromReference(string $element, array $range, array $optional, array $ignored): ?bool
    {
        if (in_array($range['ext.min'], self::$optionalreleases)) {
            return null;
        }

        if (array_key_exists('php.excludes', $range)) {
            if (in_array(PHP_VERSION, $range['php.excludes'])) {
                // We are in min/max, so add it as optional
                array_push($optional, $element);
            }
        }

        if (in_array($element, $optional) || in_array($element, $ignored)) {
            return null;
        }

        $min = $range['php.min'];
        $max = $range['php.max'];

        $emin = $range['ext.min'];
        $emax = $range['ext.max'];

        $deprecated = $range['deprecated'] ?? '';

        if (!empty($deprecated)) {
            $shouldBeThere = version_compare(PHP_VERSION, $deprecated, 'le');

            // used also for elements that were moved from one extension to another;
            // i.e with `utf8_encode` (from `xml` to `standard` extension)

            if (!$shouldBeThere) {
                return null; // ignore it !
            }
        }

        $extVersion = $this->getExtensionVersion(self::$obj->getName());

        if (!empty($min)) {
            $shouldBeThere = version_compare(PHP_VERSION, $min, 'ge');
        } else {
            $shouldBeThere = false;
        }
        if (!empty($max) && $shouldBeThere) {
            $shouldBeThere = version_compare(PHP_VERSION, $max, 'le');
        }
        if (!empty($emin) && $shouldBeThere) {
            $shouldBeThere = version_compare($extVersion, $emin, 'ge');
        }
        if (!empty($emax) && $shouldBeThere) {
            $shouldBeThere = version_compare($extVersion, $emax, 'le');
        }

        // Should be there except if set as optional
        return $shouldBeThere;
    }

    /**
     * Provider to get INI entries from an extension
     *
     * @return Generator
     */
    private function iniEntriesFromExtensionProvider()
    {
        $extension = $this->getReflectionExtension();
        $elements  = array_keys($extension->getINIEntries());

        foreach ($elements as $name) {
            yield $name;
        }
    }

    /**
     * Provider to get constants from an extension
     *
     * @return Generator
     */
    private function constantsFromExtensionProvider()
    {
        $constants = get_defined_constants(true);

        if (defined('__PHPUNIT_PHAR__')) {
            // remove '' . "\0" . '__COMPILER_HALT_OFFSET__' . "\0" . __PHPUNIT_PHAR__
            array_pop($constants['Core']);
        }

        $ext = self::$obj->getName();

        $elements = isset($constants[$ext]) ? array_keys($constants[$ext]) : [];

        foreach ($elements as $name) {
            yield $name;
        }
    }

    /**
     * Provider to get functions from extension
     *
     * @return Generator
     */
    private function functionsFromExtensionProvider()
    {
        $ext = self::$obj->getName();

        $elements = get_extension_funcs(strtolower($ext));
        if (!is_array($elements)) {
            // can be NULL for ext without function
            $elements = [];
        }

        foreach ($elements as $name) {
            yield $name;
        }
    }

    /**
     * Provider to get classes from extension
     *
     * @return Generator
     */
    private function classesFromExtensionProvider()
    {
        $extension = $this->getReflectionExtension();
        $classes   = array_unique($extension->getClassNames());
        $elements  = array_filter($classes, 'class_exists');

        foreach ($elements as $name) {
            yield $name;
        }
    }

    /**
     * Provider to get interfaces from extension
     *
     * @return Generator
     */
    private function interfacesFromExtensionProvider()
    {
        $extension = $this->getReflectionExtension();
        $classes   = array_unique($extension->getClassNames());
        $elements  = array_filter($classes, 'interface_exists');

        foreach ($elements as $name) {
            yield $name;
        }
    }

    /**
     * Provider to get class constants from extension
     *
     * @return Generator
     * @throws ReflectionException
     */
    private function classConstantsFromExtensionProvider()
    {
        $extension = $this->getReflectionExtension();
        $classes   = array_unique($extension->getClassNames());
        $elements  = [];

        foreach ($classes as $classname) {
            $class = new ReflectionClass($classname);
            if ($class->getName() != $classname) {
                /* Skip class alias */
                continue;
            }

            $elements = $elements + array_map(
                function ($value) use ($classname) {
                    return "$classname::$value";
                },
                array_keys($class->getConstants())
            );
        }

        foreach ($elements as $name) {
            yield $name;
        }
    }

    /**
     * Provider to get class methods from extension
     *
     * @return Generator
     * @throws ReflectionException
     */
    private function classMethodsFromExtensionProvider()
    {
        $extension = $this->getReflectionExtension();
        $classes   = array_unique($extension->getClassNames());
        $elements  = [];

        foreach ($classes as $classname) {
            $class   = new ReflectionClass($classname);
            if ($class->getName() != $classname) {
                /* Skip class alias */
                continue;
            }

            foreach ($class->getMethods() as $method) {
                if (!$method->isPublic()) {
                    continue;
                }
                $from = $method->getDeclaringClass()->getName();

                if ($from !== $classname) {
                    // don't check inherit methods
                    continue;
                }
                try {
                    $method->getPrototype();
                    // don't check prototype methods
                    continue;
                } catch (ReflectionException $e) {
                }

                $elements[] = $classname . '::' . $method->getName();
            }
        }

        foreach ($elements as $name) {
            yield $name;
        }
    }

    /**
     * Test than all referenced ini entries exists
     *
     * @group  reference
     * @return void
     */
    public function testGetIniEntriesFromReference()
    {
        foreach($this->provideReferenceValues(self::$obj->getIniEntries(), 'optionalcfgs') as $args) {
            list($element, $range) = $args;

            $shouldBeThere = $this->checkValuesFromReference(
                $element,
                $range,
                self::$optionalcfgs,
                self::$ignoredcfgs
            );

            if (null === $shouldBeThere) {
                // test $element should be skipped because it was marked as optional or ignored
                continue;
            }

            if ($shouldBeThere) {
                $this->assertTrue(
                    (false !== ini_get($element)),
                    "INI '$element', found in Reference, does not exists."
                );
            } else {
                $min = $range['php.min'];
                $max = $range['php.max'];

                $this->assertFalse(
                    (false !== ini_get($element)),
                    "INI '$element', found in Reference ($min, $max), exists."
                );
            }
        }
    }

    /**
     * Test that each ini entries are defined in reference
     *
     * @group  reference
     * @return void
     */
    public function testGetIniEntriesFromExtension()
    {
        $ext = self::$obj->getName();

        if ('internal' === $ext) {
            // only Core is a valid extension name for API reflection
            return;
        }

        $dict = self::$obj->getIniEntries();
        $this->assertTrue(is_array($dict));

        $generator = $this->iniEntriesFromExtensionProvider();
        if (!$generator->valid()) {
            return;
        }
        $name = $generator->current();

        if (!in_array($name, self::$ignoredcfgs)) {
            $this->assertExtensionComponentHasKey(
                $name,
                $dict,
                "Defined INI '$name' not known in Reference.",
                self::$obj
            );
        }
    }

    /**
     * Test than all referenced functions exists
     *
     * @group  reference
     * @return void
     */
    public function testGetFunctionsFromReference()
    {
        foreach ($this->provideReferenceValues(self::$obj->getFunctions(), 'optionalfunctions') as $args) {
            list($element, $range) = $args;

            $shouldBeThere = $this->checkValuesFromReference(
                $element,
                $range,
                self::$optionalfunctions,
                self::$ignoredfunctions
            );

            if (null === $shouldBeThere) {
                // test $element should be skipped because it was marked as optional or ignored
                continue;
            }

            if ($shouldBeThere) {
                try {
                    $function = new ReflectionFunction($element);
                    $this->assertTrue(
                        $function->isInternal(),
                        "Function '$element', found in Reference, does not exists."
                    );
                } catch (ReflectionException $e) {
                    // thrown if the given function does not exist.
                    $this->assertTrue(
                        false,
                        "Function '$element', found in Reference, does not exists."
                    );
                }
            } else {
                $min = $range['php.min'];
                $max = $range['php.max'];

                try {
                    $function = new ReflectionFunction($element);
                    $this->assertFalse(
                        $function->isInternal(),
                        "Function '$element', found in Reference ($min, $max), exists."
                    );
                } catch (ReflectionException $e) {
                    // thrown if the given function does not exist.
                    return;
                }
            }
        }
    }

    /**
     * Test that each functions are defined in reference
     *
     * @group  reference
     * @return void
     */
    public function testGetFunctionsFromExtension()
    {
        $dict = self::$obj->getFunctions();
        $this->assertTrue(is_array($dict));

        $generator = $this->functionsFromExtensionProvider();
        if (!$generator->valid()) {
            return;
        }
        $name = $generator->current();

        if (!in_array($name, self::$ignoredfunctions)) {
            $this->assertExtensionComponentHasKey(
                $name,
                $dict,
                "Defined function '$name' not known in Reference.",
                self::$obj
            );
        }
    }

    /**
     * Test than all referenced constants exists
     *
     * @group  reference
     * @return void
     */
    public function testGetConstantsFromReference()
    {
        foreach ($this->provideReferenceValues(self::$obj->getConstants(), 'optionalconstants') as $args) {
            list($element, $range) = $args;

            $shouldBeThere = $this->checkValuesFromReference(
                $element,
                $range,
                self::$optionalconstants,
                self::$ignoredconstants
            );

            if (null === $shouldBeThere) {
                // test $element should be skipped because it was marked as optional or ignored
                continue;
            }

            if ($shouldBeThere) {
                $this->assertTrue(
                    defined($element),
                    "Constant '$element', found in Reference, does not exists."
                );
            } else {
                $min = $range['php.min'];
                $max = $range['php.max'];

                $this->assertFalse(
                    defined($element),
                    "Constant '$element', found in Reference ($min, $max), exists."
                );
            }
        }
    }

    /**
     * Test that each constants are defined in reference
     *
     * @group  reference
     * @return void
     */
    public function testGetConstantsFromExtension()
    {
        $dict = self::$obj->getConstants();
        $this->assertTrue(is_array($dict));

        $generator = $this->constantsFromExtensionProvider();
        if (!$generator->valid()) {
            return;
        }
        $name = $generator->current();

        if (!in_array($name, self::$ignoredconstants)) {
            $this->assertArrayHasKey(
                $name,
                $dict,
                "Defined constant '$name' not known in Reference."
            );
        }
    }

    /**
     * Test than all referenced classes exists
     *
     * @group  reference
     * @return void
     */
    public function testGetClassesFromReference()
    {
        foreach ($this->provideReferenceValues(self::$obj->getClasses(), 'optionalclasses') as $args) {
            list($element, $range) = $args;

            $shouldBeThere = $this->checkValuesFromReference(
                $element,
                $range,
                self::$optionalclasses,
                self::$ignoredclasses
            );

            if (null === $shouldBeThere) {
                // test $element should be skipped because it was marked as optional or ignored
                continue;
            }

            if ($shouldBeThere) {
                $this->assertTrue(
                    class_exists($element, false),
                    "Class '$element', found in Reference, does not exists."
                );
            } else {
                $min = $range['php.min'];
                $max = $range['php.max'];

                $this->assertFalse(
                    class_exists($element, false),
                    "Class '$element', found in Reference ($min, $max), exists."
                );
            }
        }
    }

    /**
     * Test that each classes are defined in reference
     *
     * @group  reference
     * @return void
     */
    public function testGetClassesFromExtension()
    {
        $dict = self::$obj->getClasses();
        $this->assertTrue(is_array($dict));

        $generator = $this->classesFromExtensionProvider();
        if (!$generator->valid()) {
            return;
        }
        $name = $generator->current();

        if (!in_array($name, self::$ignoredclasses)) {
            $this->assertArrayHasKey(
                $name,
                $dict,
                "Defined class '$name' not known in Reference."
            );
        }
    }

    /**
     * Test than all referenced class methods exists
     *
     * @group  reference
     * @return void
     */
    public function testGetClassMethodsFromReference()
    {
        $elements = [];

        $methods = array_merge(
            self::$obj->getClassMethods(),
            self::$obj->getClassStaticMethods()
        );

        foreach ($methods as $class => $values) {
            foreach ($values as $method => $range) {
                $elements[$class.'::'.$method] = $range;
            }
        }

        foreach ($this->provideReferenceValues($elements, 'optionalmethods') as $args) {
            list($element, $range) = $args;

            $shouldBeThere = $this->checkValuesFromReference(
                $element,
                $range,
                self::$optionalmethods,
                self::$ignoredmethods
            );

            if (null === $shouldBeThere) {
                // test $element should be skipped because it was marked as optional or ignored
                continue;
            }

            if ($shouldBeThere) {
                list ($object, $method) = explode('::', $element);
                $this->assertTrue(
                    method_exists($object, $method),
                    "Class Method '$element', found in Reference, does not exists."
                );
            } else {
                list ($object, $method) = explode('::', $element);
                try {
                    $method = new ReflectionMethod($object, $method);
                    $this->assertFalse(
                        $method->getDeclaringClass() === $object,
                        "Class Method '$element', found in Reference, exists."
                    );
                } catch (ReflectionException $e) {
                    // thrown if the given method does not exist.
                    return;
                }
            }
        }
    }

    /**
     * Test that each class methods are defined in reference
     *
     * @group  reference
     * @return void
     * @throws ReflectionException
     */
    public function testGetClassMethodsFromExtension()
    {
        $generator = $this->classMethodsFromExtensionProvider();
        if (!$generator->valid()) {
            return;
        }
        $name = $generator->current();

        if (in_array($name, self::$ignoredmethods)) {
            return;
        }

        $nonStaticMethods = self::$obj->getClassMethods();
        $this->assertTrue(is_array($nonStaticMethods));
        $staticMethods    = self::$obj->getClassStaticMethods();
        $this->assertTrue(is_array($staticMethods));

        $this->assertNotEquals(
            0 ,
            count($nonStaticMethods) + count($staticMethods),
            'None method defined. Checks if `*.methods.json` file exists.'
        );

        list ($classname, $name) = explode('::', $name);

        if (isset($nonStaticMethods[$classname])) {
            $this->assertArrayHasKey(
                $name,
                $nonStaticMethods[$classname],
                "Defined method '$classname::$name' not known in Reference."
            );
        } else {
            $this->assertArrayHasKey(
                $name,
                $staticMethods[$classname],
                "Defined static method '$classname::$name' not known in Reference."
            );
        }
    }

    /**
     * Test that each class constants are defined in reference
     *
     * @group  reference
     * @return void
     * @throws ReflectionException
     */
    public function testGetClassConstantsFromExtension()
    {
        $dict = self::$obj->getClassConstants();
        $this->assertTrue(is_array($dict));

        $generator = $this->classConstantsFromExtensionProvider();
        if (!$generator->valid()) {
            return;
        }
        $name = $generator->current();

        list ($classname, $name) = explode('::', $name);

        if (!in_array($name, self::$ignoredconsts)) {
            $this->assertExtensionComponentHasKey(
                $name,
                $dict[$classname],
                "Defined class constant '$classname::$name' not known in Reference.",
                self::$obj
            );
        }
    }

    /**
     * Test than all referenced interfaces exists
     *
     * @group  reference
     * @return void
     */
    public function testGetInterfacesFromReference()
    {
        foreach ($this->provideReferenceValues(self::$obj->getInterfaces(), 'optionalinterfaces') as $args) {
            list($element, $range) = $args;

            $shouldBeThere = $this->checkValuesFromReference(
                $element,
                $range,
                self::$optionalinterfaces,
                self::$ignoredinterfaces
            );

            if (null === $shouldBeThere) {
                // test $element should be skipped because it was marked as optional or ignored
                continue;
            }

            if ($shouldBeThere) {
                $this->assertTrue(
                    interface_exists($element, false),
                    "Interface '$element', found in Reference, does not exists."
                );
            } else {
                $min = $range['php.min'];
                $max = $range['php.max'];

                $this->assertFalse(
                    interface_exists($element, false),
                    "Interface '$element', found in Reference ($min, $max), exists."
                );
            }
        }
    }

    /**
     * Test that each interface is defined in reference
     *
     * @group  reference
     * @return void
     */
    public function testGetInterfacesFromExtension()
    {
        $dict = self::$obj->getInterfaces();
        $this->assertTrue(is_array($dict));

        $generator = $this->interfacesFromExtensionProvider();
        if (!$generator->valid()) {
            return;
        }
        $name = $generator->current();

        if (!in_array($name, self::$ignoredinterfaces)) {
            $this->assertExtensionComponentHasKey(
                $name,
                $dict,
                "Defined interface '$name' not known in Reference.",
                self::$obj
            );
        }
    }

    private function assertExtensionComponentHasKey($key, $array, $message, $obj)
    {
        try {
            $this->assertArrayHasKey($key, $array, $message);
        } catch (ExpectationFailedException $e) {
            $warning = $this->checkUpdateExtension($obj);

            if (is_string($warning)) {
                $this->markTestSkipped($warning);
            } else {
                throw $e;
            }
        }
    }

    private function checkUpdateExtension(ReferenceInterface $obj): ?string
    {
        $currentVersion = $obj->getCurrentVersion();

        if ($currentVersion === false) {
            // extension did not provide any version information
            return null;
        }

        $releases = array_keys($obj->getReleases());

        $latestReleaseReferenced = array_pop($releases);
        // check if extension installed is more recent than the one declared in compatinfo-db
        if (version_compare($currentVersion, $latestReleaseReferenced, 'le')) {
            return null;
        }

        return sprintf(
            'Extension %s tested is version %s, while latest version referenced is %s and has need update.',
            $obj->getName(),
            $obj->getCurrentVersion(),
            $latestReleaseReferenced
        );
    }

    private function getReflectionExtension(): ReflectionExtension
    {
        $name = self::$obj->getName();
        // special case(s)
        if ('opcache' === $name) {
            $name = 'zend opcache';
        }
        return new ReflectionExtension($name);
    }
}

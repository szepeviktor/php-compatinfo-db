<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Presentation\Console;

use Bartlett\CompatInfoDb\DatabaseFactory;

use PackageVersions\Versions;

use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

use PDO;

/**
 * Symfony Console Application to handle the SQLite compatinfo database.
 */
class Application extends SymfonyApplication implements ApplicationInterface
{
    /** @var string */
    private $baseDir;

    /** @var ContainerInterface  */
    private $container;

    public function __construct(string $version = 'UNKNOWN')
    {
        if ('UNKNOWN' === $version) {
            // composer or git outside world strategy
            $version = self::VERSION;
        } elseif (substr_count($version, '.') === 2) {
            // release is in X.Y.Z format
        } else {
            // composer or git strategy
            $version = Versions::getVersion('bartlett/php-compatinfo-db');
            list($ver, ) = explode('@', $version);

            if (strpos($ver, 'dev') === false) {
                $version = $ver;
            }
        }
        parent::__construct(self::NAME, $version);

        $this->baseDir = dirname(__DIR__, 3);
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultInputDefinition(): InputDefinition
    {
        $definition = parent::getDefaultInputDefinition();
        if (\Phar::running()) {
            // handle external configuration files is not allowed with PHAR distribution
            return $definition;
        }
        $definition->addOption(
            new InputOption(
                'config',
                'c',
                InputOption::VALUE_REQUIRED,
                'Read configuration from PHP file'
            )
        );
        $definition->addOption(
            new InputOption(
                'no-configuration',
                null,
                InputOption::VALUE_NONE,
                'Ignore current configuration and run with only required services (config/set/common.php)'
            )
        );
        return $definition;
    }

    /**
     * {@inheritDoc}
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        if (null === $input) {
            if ($this->container->has(InputInterface::class)) {
                $input = $this->container->get(InputInterface::class);
            } else {
                $input = new ArgvInput();
            }


            if ($input->hasParameterOption('--no-configuration')) {
                $configFile = 'config/set/common.php';
            } else {
                $configFile = $input->getParameterOption('-c');
            }

            if (false === $configFile) {
                $configFile = $input->getParameterOption('--config');
            }
            if (false !== $configFile) {
                $containerBuilder = new ContainerBuilder();
                try {
                    $loader = new PhpFileLoader($containerBuilder, new FileLocator(dirname($configFile)));
                    $loader->load(basename($configFile));
                } catch (FileLocatorFileNotFoundException $e) {
                    $output = new ConsoleOutput();
                    $this->renderThrowable($e, $output);
                    return 1;
                }
                $containerBuilder->compile();
                $this->setContainer($containerBuilder);
            }
        }

        if (null === $output) {
            if ($this->container->has(OutputInterface::class)) {
                $output = $this->container->get(OutputInterface::class);
            } else {
                $output = new ConsoleOutput();
            }
        }

        return parent::run($input, $output);
    }

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getDbFilename() : string
    {
        return $this->baseDir . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'compatinfo.sqlite';
    }

    public function getRefDir() : string
    {
        return $this->baseDir . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'references';
    }

    public function getLongVersion() : string
    {
        if ('UNKNOWN' !== $this->getName()) {
            if ('UNKNOWN' !== $this->getVersion()) {
                $v = $this->getDbVersions();

                return sprintf(
                    '<info>%s</info> version <comment>%s</comment> DB built <comment>%s</comment>',
                    $this->getName(),
                    $this->getVersion(),
                    $v['build.string']
                );
            }

            return $this->getName();
        }

        return 'Console Tool';
    }

    public function getDbVersions() : array
    {
        $pdo = DatabaseFactory::create('sqlite');

        $stmt = $pdo->prepare(
            'SELECT build_string as "build.string", build_date as "build.date", build_version as "build.version"' .
            ' FROM bartlett_compatinfo_versions'
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}

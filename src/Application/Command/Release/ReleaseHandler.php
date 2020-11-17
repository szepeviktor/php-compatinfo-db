<?php declare(strict_types=1);

/**
 * Handler to add a new PHP release in database.
 *
 * PHP version 7
 *
 * @category   PHP
 * @package    PHP_CompatInfo_Db
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @license    https://opensource.org/licenses/BSD-3-Clause The 3-Clause BSD License
 * @link       http://bartlett.laurent-laville.org/php-compatinfo/
 */

namespace Bartlett\CompatInfoDb\Application\Command\Release;

use Bartlett\CompatInfoDb\Application\Command\CommandHandlerInterface;
use Bartlett\CompatInfoDb\Application\Service\JsonFileHandler;
use Bartlett\CompatInfoDb\Domain\Factory\ExtensionVersionProviderInterface;

use RuntimeException;
use function array_pop;
use function json_last_error;
use function sprintf;
use function sscanf;
use const JSON_ERROR_NONE;

class ReleaseHandler implements CommandHandlerInterface
{
    private $latestPhpVersion = [
        '52' => ExtensionVersionProviderInterface::LATEST_PHP_5_2,
        '53' => ExtensionVersionProviderInterface::LATEST_PHP_5_3,
        '54' => ExtensionVersionProviderInterface::LATEST_PHP_5_4,
        '55' => ExtensionVersionProviderInterface::LATEST_PHP_5_5,
        '56' => ExtensionVersionProviderInterface::LATEST_PHP_5_6,
        '70' => ExtensionVersionProviderInterface::LATEST_PHP_7_0,
        '71' => ExtensionVersionProviderInterface::LATEST_PHP_7_1,
        '72' => ExtensionVersionProviderInterface::LATEST_PHP_7_2,
        '73' => ExtensionVersionProviderInterface::LATEST_PHP_7_3,
        '74' => ExtensionVersionProviderInterface::LATEST_PHP_7_4,
        '80' => ExtensionVersionProviderInterface::LATEST_PHP_8_0,
    ];

    private $jsonFileHandler;

    public function __construct(JsonFileHandler $jsonFileHandler)
    {
        $this->jsonFileHandler = $jsonFileHandler;
    }

    public function __invoke(ReleaseCommand $command): void
    {
        $relVersion = $command->relVersion;
        list($maj, $min, $rel) = sscanf($relVersion, '%d.%d.%s');

        $this->latestPhpVersion[$maj.$min] = $relVersion;

        $this->addNewPhpVersion($maj, $min, $relVersion, $command->relDate, $command->relState);
        $this->tagPhpMaxVersion();
    }

    private function addNewPhpVersion($phpMaj, $phpMin, $relVersion, $relDate, $relState): void
    {
        $release = [];

        $extId   = 7;
        $refName = 'Core';
        $ext     = 'releases';
        $major   = $phpMaj . $phpMin;
        $release[] = array($extId, $refName, $ext, $major);

        $extId   = 78;
        $refName = 'Standard';
        $release[] = array($extId, $refName, $ext, $major);

        // @see  i.e: opcache extension version is now PHP version since 7.0.8RC1
        // @link https://github.com/llaville/php-compatinfo-db/issues/5
        $extId   = 100;
        $refName = 'Zendopcache';
        $release[] = array($extId, $refName, $ext, $major);

        // bcmath extension version is now PHP version since 7.0.0alpha1
        $extId   = 4;
        $refName = 'Bcmath';
        $release[] = array($extId, $refName, $ext, $major);

        // bz2 extension version is now PHP version since 7.0.0alpha1
        $extId   = 5;
        $refName = 'Bz2';
        $release[] = array($extId, $refName, $ext, $major);

        // calendar extension version is now PHP version since 7.0.0alpha1
        $extId   = 6;
        $refName = 'Calendar';
        $release[] = array($extId, $refName, $ext, $major);

        // ctype extension version is now PHP version since 7.0.0alpha1
        $extId   = 8;
        $refName = 'Ctype';
        $release[] = array($extId, $refName, $ext, $major);

        // curl extension version is now PHP version since 7.0.0alpha1
        $extId   = 9;
        $refName = 'Curl';
        $release[] = array($extId, $refName, $ext, $major);

        // date extension version is now PHP version since 7.0.0alpha1
        $extId   = 10;
        $refName = 'Date';
        $release[] = array($extId, $refName, $ext, $major);

        // dom extension version does not follow PHP Version
        $extId   = 11;

        // enchant extension version does not follow PHP Version
        $extId   = 12;

        // ereg extension was deprecated since PHP 5.3 and was removed in PHP 7
        $extId   = 13;

        // exif extension version does not follow PHP Version
        $extId   = 14;

        // fileinfo extension version does not follow PHP Version
        $extId   = 15;

        // filter extension version is now PHP version since 7.0.0alpha1
        $extId   = 16;
        $refName = 'Filter';
        $release[] = array($extId, $refName, $ext, $major);

        // ftp extension version is now PHP version since 7.0.0alpha1
        $extId   = 17;
        $refName = 'Ftp';
        $release[] = array($extId, $refName, $ext, $major);

        // gd extension version is now PHP version since 7.0.0alpha1
        $extId   = 18;
        $refName = 'Gd';
        $release[] = array($extId, $refName, $ext, $major);

        // geoip extension version does not follow PHP Version
        $extId   = 20;

        // gmp extension version is now PHP version since 7.0.0alpha1
        $extId   = 22;
        $refName = 'Gmp';
        $release[] = array($extId, $refName, $ext, $major);

        // intl extension version does not follow PHP Version
        $extId   = 32;

        // ldap extension version is now PHP version since 7.0.0alpha1
        $extId   = 35;
        $refName = 'Ldap';
        $release[] = array($extId, $refName, $ext, $major);

        // lzf extension version does not follow PHP Version
        $extId   = 38;

        // mailparse extension version does not follow PHP Version
        $extId   = 39;

        // mbstring extension version is now PHP version since 7.0.0alpha1
        $extId   = 40;
        $refName = 'Mbstring';
        $release[] = array($extId, $refName, $ext, $major);

        // mysqli extension version is now PHP version since 7.0.0alpha1
        $extId   = 49;
        $refName = 'Mysqli';
        $release[] = array($extId, $refName, $ext, $major);

        // openssl extension version is now PHP version since 7.0.0alpha1
        $extId   = 52;
        $refName = 'Openssl';
        $release[] = array($extId, $refName, $ext, $major);

        // pgsql extension version is now PHP version since 7.0.0alpha1
        $extId   = 57;
        $refName = 'Pgsql';
        $release[] = array($extId, $refName, $ext, $major);

        // session extension version is now PHP version since 7.0.0alpha1
        $extId   = 66;
        $refName = 'Session';
        $release[] = array($extId, $refName, $ext, $major);

        // shmop extension version is now PHP version since 7.0.0alpha1
        $extId   = 67;
        $refName = 'Shmop';
        $release[] = array($extId, $refName, $ext, $major);

        // soap extension version is now PHP version since 7.0.0alpha1
        $extId   = 70;
        $refName = 'Soap';
        $release[] = array($extId, $refName, $ext, $major);

        // sockets extension version is now PHP version since 7.0.0alpha1
        $extId   = 71;
        $refName = 'Sockets';
        $release[] = array($extId, $refName, $ext, $major);

        // spl extension version is now PHP version since 7.0.0alpha1
        $extId   = 74;
        $refName = 'Spl';
        $release[] = array($extId, $refName, $ext, $major);

        // sqlite3 extension version is now PHP version since 7.0.0alpha1
        $extId   = 75;
        $refName = 'Sqlite3';
        $release[] = array($extId, $refName, $ext, $major);

        // tidy extension version is now PHP version since 7.0.0alpha1
        $extId   = 85;
        $refName = 'Tidy';
        $release[] = array($extId, $refName, $ext, $major);

        // xmlrpc extension version is now PHP version since 7.0.0alpha1
        $extId   = 95;
        $refName = 'Xmlrpc';
        $release[] = array($extId, $refName, $ext, $major);

        // xsl extension version is now PHP version since 7.0.0alpha1
        $extId   = 97;
        $refName = 'Xsl';
        $release[] = array($extId, $refName, $ext, $major);

        // Add NEW release on each extensions that follow PHP version tagging strategy
        while (!empty($release)) {
            list($extId, $refName, $ext, $major) = array_pop($release);

            $data = $this->jsonFileHandler->read($refName, $ext, $major);

            if (!$data) {
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $error = sprintf('Cannot decode file %s.%s.json', $refName . $major, $ext);
                    throw new RuntimeException($error);
                }
                $data = [];
            }

            $data[] = [
                'ext_name_fk'   => $extId,
                'rel_version'   => $relVersion,
                'rel_date'      => $relDate,
                'rel_state'     => $relState,
                'ext_max'       => '',
                'php_min'       => $relVersion,
                'php_max'       => '',
            ];
            $this->jsonFileHandler->write($refName, $ext, $major, $data);
        }
    }

    private function tagPhpMaxVersion(): void
    {
        $latest  = [];

        $refName = 'Curl';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'CURLCLOSEPOLICY_CALLBACK'              => $this->latestPhpVersion['55'],
            'CURLCLOSEPOLICY_LEAST_RECENTLY_USED'   => $this->latestPhpVersion['55'],
            'CURLCLOSEPOLICY_LEAST_TRAFFIC'         => $this->latestPhpVersion['55'],
            'CURLCLOSEPOLICY_OLDEST'                => $this->latestPhpVersion['55'],
            'CURLCLOSEPOLICY_SLOWEST'               => $this->latestPhpVersion['55'],
            'CURLOPT_CLOSEPOLICY'                   => $this->latestPhpVersion['55'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Core';
        $ext     = 'iniEntries';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'allow_call_time_pass_reference'        => $this->latestPhpVersion['53'],
            'define_syslog_variables'               => $this->latestPhpVersion['53'],
            'highlight.bg'                          => $this->latestPhpVersion['53'],
            'magic_quotes_gpc'                      => $this->latestPhpVersion['53'],
            'magic_quotes_runtime'                  => $this->latestPhpVersion['53'],
            'magic_quotes_sybase'                   => $this->latestPhpVersion['53'],
            'register_globals'                      => $this->latestPhpVersion['53'],
            'safe_mode'                             => $this->latestPhpVersion['53'],
            'safe_mode_exec_dir'                    => $this->latestPhpVersion['53'],
            'y2k_compliance'                        => $this->latestPhpVersion['53'],
            'safe_mode_gid'                         => $this->latestPhpVersion['53'],
            'safe_mode_include_dir'                 => $this->latestPhpVersion['53'],

            'always_populate_raw_post_data'         => $this->latestPhpVersion['56'],
            'asp_tags'                              => $this->latestPhpVersion['56'],

            'exit_on_timeout'                       => $this->latestPhpVersion['70'],

            'sql.safe_mode'                         => $this->latestPhpVersion['71'],

            'track_errors'                          => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Core';
        $ext     = 'iniEntries';
        $major   = '5';
        $entry   = 'php_max';
        $names   = array(
            'register_long_arrays'                  => $this->latestPhpVersion['53'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Core';
        $ext     = 'functions';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'create_function'                       => $this->latestPhpVersion['74'],
            'each'                                  => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Core';
        $ext     = 'constants';
        $major   = '5';
        $entry   = 'php_max';
        $names   = array(
            'ZEND_MULTIBYTE'                        => $this->latestPhpVersion['53'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Dom';
        $ext     = 'classes';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'DOMConfiguration'                      => $this->latestPhpVersion['74'],
            'DOMDomError'                           => $this->latestPhpVersion['74'],
            'DOMErrorHandler'                       => $this->latestPhpVersion['74'],
            'DOMImplementationList'                 => $this->latestPhpVersion['74'],
            'DOMImplementationSource'               => $this->latestPhpVersion['74'],
            'DOMLocator'                            => $this->latestPhpVersion['74'],
            'DOMNameList'                           => $this->latestPhpVersion['74'],
            'DOMStringExtend'                       => $this->latestPhpVersion['74'],
            'DOMStringList'                         => $this->latestPhpVersion['74'],
            'DOMTypeinfo'                           => $this->latestPhpVersion['74'],
            'DOMUserDataHandler'                    => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Dom';
        $ext     = 'methods';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'DOMConfiguration::canSetParameter'     => $this->latestPhpVersion['74'],
            'DOMConfiguration::getParameter'        => $this->latestPhpVersion['74'],
            'DOMConfiguration::setParameter'        => $this->latestPhpVersion['74'],
            'DOMDocument::renameNode'               => $this->latestPhpVersion['74'],
            'DOMErrorHandler::handleError'          => $this->latestPhpVersion['74'],
            'DOMImplementationList::item'           => $this->latestPhpVersion['74'],
            'DOMImplementationSource::getDomimplementation'  => $this->latestPhpVersion['74'],
            'DOMImplementationSource::getDomimplementations' => $this->latestPhpVersion['74'],
            'DOMNameList::getName'                  => $this->latestPhpVersion['74'],
            'DOMNameList::getNamespaceURI'          => $this->latestPhpVersion['74'],
            'DOMNamedNodeMap::removeNamedItem'      => $this->latestPhpVersion['74'],
            'DOMNamedNodeMap::removeNamedItemNS'    => $this->latestPhpVersion['74'],
            'DOMNamedNodeMap::setNamedItem'         => $this->latestPhpVersion['74'],
            'DOMNamedNodeMap::setNamedItemNS'       => $this->latestPhpVersion['74'],
            'DOMNode::compareDocumentPosition'      => $this->latestPhpVersion['74'],
            'DOMNode::getFeature'                   => $this->latestPhpVersion['74'],
            'DOMNode::getUserData'                  => $this->latestPhpVersion['74'],
            'DOMNode::isEqualNode'                  => $this->latestPhpVersion['74'],
            'DOMNode::setUserData'                  => $this->latestPhpVersion['74'],
            'DOMStringExtend::findOffset16'         => $this->latestPhpVersion['74'],
            'DOMStringExtend::findOffset32'         => $this->latestPhpVersion['74'],
            'DOMStringList::item'                   => $this->latestPhpVersion['74'],
            'DOMText::replaceWholeText'             => $this->latestPhpVersion['74'],
            'DOMUserDataHandler::handle'            => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Exif';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'read_exif_data'                        => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Fileinfo';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'FILEINFO_COMPRESS'                     => $this->latestPhpVersion['52'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Fileinfo';
        $ext     = 'methods';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'finfo::finfo'                          => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Filter';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'FILTER_FLAG_HOST_REQUIRED'             => $this->latestPhpVersion['74'],
            'FILTER_FLAG_SCHEME_REQUIRED'           => $this->latestPhpVersion['74'],
            'FILTER_SANITIZE_MAGIC_QUOTES'          => $this->latestPhpVersion['74'],
            'INPUT_REQUEST'                         => $this->latestPhpVersion['74'],
            'INPUT_SESSION'                         => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Gd';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'image2wbmp'                            => $this->latestPhpVersion['74'],
            'jpeg2wbmp'                             => $this->latestPhpVersion['74'],
            'png2wbmp'                              => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Gmp';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'gmp_random'                            => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'releases';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '0.7.0'                                 => $this->latestPhpVersion['55'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'releases';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            '1.0.0'                                 => $this->latestPhpVersion['55'],
            '1.3.0'                                 => $this->latestPhpVersion['55'],
            '1.5.0'                                 => $this->latestPhpVersion['55'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'classes';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'HttpRequest'                           => $this->latestPhpVersion['55'],
            'HttpResponse'                          => $this->latestPhpVersion['55'],
            'HttpUtil'                              => $this->latestPhpVersion['55'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'classes';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            'HttpDeflateStream'                     => $this->latestPhpVersion['55'],
            'HttpEncodingException'                 => $this->latestPhpVersion['55'],
            'HttpException'                         => $this->latestPhpVersion['55'],
            'HttpHeaderException'                   => $this->latestPhpVersion['55'],
            'HttpInflateStream'                     => $this->latestPhpVersion['55'],
            'HttpInvalidParamException'             => $this->latestPhpVersion['55'],
            'HttpMalformedHeadersException'         => $this->latestPhpVersion['55'],
            'HttpMessage'                           => $this->latestPhpVersion['55'],
            'HttpMessageTypeException'              => $this->latestPhpVersion['55'],
            'HttpQueryString'                       => $this->latestPhpVersion['55'],
            'HttpQueryStringException'              => $this->latestPhpVersion['55'],
            'HttpRequestException'                  => $this->latestPhpVersion['55'],
            'HttpRequestMethodException'            => $this->latestPhpVersion['55'],
            'HttpRequestPool'                       => $this->latestPhpVersion['55'],
            'HttpRequestPoolException'              => $this->latestPhpVersion['55'],
            'HttpResponseException'                 => $this->latestPhpVersion['55'],
            'HttpRuntimeException'                  => $this->latestPhpVersion['55'],
            'HttpSocketException'                   => $this->latestPhpVersion['55'],
            'HttpUrlException'                      => $this->latestPhpVersion['55'],
            'HttpRequestDataShare'                  => $this->latestPhpVersion['55'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '*'                                     => $this->latestPhpVersion['55'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '*'                                     => $this->latestPhpVersion['55'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '*'                                     => $this->latestPhpVersion['55'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Iconv';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'ob_iconv_handler'                      => $this->latestPhpVersion['53'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Intl';
        $ext     = 'functions';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            'datefmt_set_timezone_id'               => $this->latestPhpVersion['56'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Intl';
        $ext     = 'methods';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            'IntlDateFormatter::setTimeZoneId'      => $this->latestPhpVersion['56'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Intl';
        $ext     = 'constants';
        $major   = '2';
        $entry   = 'php_max';
        $names   = array(
            'INTL_IDNA_VARIANT_2003'                => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Ldap';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'ldap_control_paged_result'             => $this->latestPhpVersion['74'],
            'ldap_control_paged_result_response'    => $this->latestPhpVersion['74'],
            'ldap_sort'                             => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mbstring';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'mbstring.func_overload'                => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mbstring';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'mbereg'                                => $this->latestPhpVersion['74'],
            'mbereg_match'                          => $this->latestPhpVersion['74'],
            'mbereg_replace'                        => $this->latestPhpVersion['74'],
            'mbereg_search'                         => $this->latestPhpVersion['74'],
            'mbereg_search_getpos'                  => $this->latestPhpVersion['74'],
            'mbereg_search_getregs'                 => $this->latestPhpVersion['74'],
            'mbereg_search_init'                    => $this->latestPhpVersion['74'],
            'mbereg_search_pos'                     => $this->latestPhpVersion['74'],
            'mbereg_search_regs'                    => $this->latestPhpVersion['74'],
            'mbereg_search_setpos'                  => $this->latestPhpVersion['74'],
            'mberegi'                               => $this->latestPhpVersion['74'],
            'mberegi_replace'                       => $this->latestPhpVersion['74'],
            'mbregex_encoding'                      => $this->latestPhpVersion['74'],
            'mbsplit'                               => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mbstring';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'MB_OVERLOAD_MAIL'                      => $this->latestPhpVersion['74'],
            'MB_OVERLOAD_REGEX'                     => $this->latestPhpVersion['74'],
            'MB_OVERLOAD_STRING'                    => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mcrypt';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'MCRYPT_3DES'                           => $this->latestPhpVersion['71'],
            'MCRYPT_ARCFOUR'                        => $this->latestPhpVersion['71'],
            'MCRYPT_ARCFOUR_IV'                     => $this->latestPhpVersion['71'],
            'MCRYPT_BLOWFISH'                       => $this->latestPhpVersion['71'],
            'MCRYPT_CAST_128'                       => $this->latestPhpVersion['71'],
            'MCRYPT_CAST_256'                       => $this->latestPhpVersion['71'],
            'MCRYPT_CRYPT'                          => $this->latestPhpVersion['71'],
            'MCRYPT_DECRYPT'                        => $this->latestPhpVersion['71'],
            'MCRYPT_DES'                            => $this->latestPhpVersion['71'],
            'MCRYPT_DES_COMPAT'                     => $this->latestPhpVersion['71'],
            'MCRYPT_DEV_RANDOM'                     => $this->latestPhpVersion['71'],
            'MCRYPT_DEV_URANDOM'                    => $this->latestPhpVersion['71'],
            'MCRYPT_ENCRYPT'                        => $this->latestPhpVersion['71'],
            'MCRYPT_ENIGNA'                         => $this->latestPhpVersion['71'],
            'MCRYPT_GOST'                           => $this->latestPhpVersion['71'],
            'MCRYPT_IDEA'                           => $this->latestPhpVersion['71'],
            'MCRYPT_LOKI97'                         => $this->latestPhpVersion['71'],
            'MCRYPT_MARS'                           => $this->latestPhpVersion['71'],
            'MCRYPT_MODE_CBC'                       => $this->latestPhpVersion['71'],
            'MCRYPT_MODE_CFB'                       => $this->latestPhpVersion['71'],
            'MCRYPT_MODE_ECB'                       => $this->latestPhpVersion['71'],
            'MCRYPT_MODE_NOFB'                      => $this->latestPhpVersion['71'],
            'MCRYPT_MODE_OFB'                       => $this->latestPhpVersion['71'],
            'MCRYPT_MODE_STREAM'                    => $this->latestPhpVersion['71'],
            'MCRYPT_PANAMA'                         => $this->latestPhpVersion['71'],
            'MCRYPT_RAND'                           => $this->latestPhpVersion['71'],
            'MCRYPT_RC2'                            => $this->latestPhpVersion['71'],
            'MCRYPT_RC4'                            => $this->latestPhpVersion['71'],
            'MCRYPT_RC6'                            => $this->latestPhpVersion['71'],
            'MCRYPT_RC6_128'                        => $this->latestPhpVersion['71'],
            'MCRYPT_RC6_192'                        => $this->latestPhpVersion['71'],
            'MCRYPT_RC6_256'                        => $this->latestPhpVersion['71'],
            'MCRYPT_RIJNDAEL_128'                   => $this->latestPhpVersion['71'],
            'MCRYPT_RIJNDAEL_192'                   => $this->latestPhpVersion['71'],
            'MCRYPT_RIJNDAEL_256'                   => $this->latestPhpVersion['71'],
            'MCRYPT_SAFER128'                       => $this->latestPhpVersion['71'],
            'MCRYPT_SAFER64'                        => $this->latestPhpVersion['71'],
            'MCRYPT_SAFERPLUS'                      => $this->latestPhpVersion['71'],
            'MCRYPT_SERPENT'                        => $this->latestPhpVersion['71'],
            'MCRYPT_SERPENT_128'                    => $this->latestPhpVersion['71'],
            'MCRYPT_SERPENT_192'                    => $this->latestPhpVersion['71'],
            'MCRYPT_SERPENT_256'                    => $this->latestPhpVersion['71'],
            'MCRYPT_SKIPJACK'                       => $this->latestPhpVersion['71'],
            'MCRYPT_TEAN'                           => $this->latestPhpVersion['71'],
            'MCRYPT_THREEWAY'                       => $this->latestPhpVersion['71'],
            'MCRYPT_TRIPLEDES'                      => $this->latestPhpVersion['71'],
            'MCRYPT_TWOFISH'                        => $this->latestPhpVersion['71'],
            'MCRYPT_TWOFISH128'                     => $this->latestPhpVersion['71'],
            'MCRYPT_TWOFISH192'                     => $this->latestPhpVersion['71'],
            'MCRYPT_TWOFISH256'                     => $this->latestPhpVersion['71'],
            'MCRYPT_WAKE'                           => $this->latestPhpVersion['71'],
            'MCRYPT_XTEA'                           => $this->latestPhpVersion['71'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);
        $entry   = 'php_max';
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mcrypt';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'mcrypt_cbc'                            => $this->latestPhpVersion['71'],
            'mcrypt_cfb'                            => $this->latestPhpVersion['71'],
            'mcrypt_create_iv'                      => $this->latestPhpVersion['71'],
            'mcrypt_decrypt'                        => $this->latestPhpVersion['71'],
            'mcrypt_ecb'                            => $this->latestPhpVersion['71'],
            'mcrypt_enc_get_algorithms_name'        => $this->latestPhpVersion['71'],
            'mcrypt_enc_get_block_size'             => $this->latestPhpVersion['71'],
            'mcrypt_enc_get_iv_size'                => $this->latestPhpVersion['71'],
            'mcrypt_enc_get_key_size'               => $this->latestPhpVersion['71'],
            'mcrypt_enc_get_modes_name'             => $this->latestPhpVersion['71'],
            'mcrypt_enc_get_supported_key_sizes'    => $this->latestPhpVersion['71'],
            'mcrypt_enc_is_block_algorithm'         => $this->latestPhpVersion['71'],
            'mcrypt_enc_is_block_algorithm_mode'    => $this->latestPhpVersion['71'],
            'mcrypt_enc_is_block_mode'              => $this->latestPhpVersion['71'],
            'mcrypt_enc_self_test'                  => $this->latestPhpVersion['71'],
            'mcrypt_encrypt'                        => $this->latestPhpVersion['71'],
            'mcrypt_generic'                        => $this->latestPhpVersion['71'],
            'mcrypt_generic_deinit'                 => $this->latestPhpVersion['71'],
            'mcrypt_generic_end'                    => $this->latestPhpVersion['71'],
            'mcrypt_generic_init'                   => $this->latestPhpVersion['71'],
            'mcrypt_get_block_size'                 => $this->latestPhpVersion['71'],
            'mcrypt_get_cipher_name'                => $this->latestPhpVersion['71'],
            'mcrypt_get_iv_size'                    => $this->latestPhpVersion['71'],
            'mcrypt_get_key_size'                   => $this->latestPhpVersion['71'],
            'mcrypt_list_algorithms'                => $this->latestPhpVersion['71'],
            'mcrypt_list_modes'                     => $this->latestPhpVersion['71'],
            'mcrypt_module_close'                   => $this->latestPhpVersion['71'],
            'mcrypt_module_get_algo_block_size'     => $this->latestPhpVersion['71'],
            'mcrypt_module_get_algo_key_size'       => $this->latestPhpVersion['71'],
            'mcrypt_module_get_supported_key_sizes' => $this->latestPhpVersion['71'],
            'mcrypt_module_is_block_algorithm'      => $this->latestPhpVersion['71'],
            'mcrypt_module_is_block_algorithm_mode' => $this->latestPhpVersion['71'],
            'mcrypt_module_is_block_mode'           => $this->latestPhpVersion['71'],
            'mcrypt_module_open'                    => $this->latestPhpVersion['71'],
            'mcrypt_module_self_test'               => $this->latestPhpVersion['71'],
            'mcrypt_ofb'                            => $this->latestPhpVersion['71'],
            'mdecrypt_generic'                      => $this->latestPhpVersion['71'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);
        $entry   = 'php_max';
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mcrypt';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'mcrypt.algorithms_dir'                 => $this->latestPhpVersion['71'],
            'mcrypt.modes_dir'                      => $this->latestPhpVersion['71'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);
        $entry   = 'php_max';
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mcrypt';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'mcrypt_ecb'                            => $this->latestPhpVersion['56'],
            'mcrypt_cbc'                            => $this->latestPhpVersion['56'],
            'mcrypt_cfb'                            => $this->latestPhpVersion['56'],
            'mcrypt_ofb'                            => $this->latestPhpVersion['56'],
            'mcrypt_generic_end'                    => $this->latestPhpVersion['56'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Memcached';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'memcached.sess_binary'                 => $this->latestPhpVersion['74'],
            'memcached.sess_remove_failed'          => $this->latestPhpVersion['74'],
            'memcached.use_sasl'                    => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mysqli';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'mysqli_bind_param'                     => $this->latestPhpVersion['53'],
            'mysqli_bind_result'                    => $this->latestPhpVersion['53'],
            'mysqli_client_encoding'                => $this->latestPhpVersion['53'],
            'mysqli_disable_reads_from_master'      => $this->latestPhpVersion['52'],
            'mysqli_disable_rpl_parse'              => $this->latestPhpVersion['52'],
            'mysqli_enable_reads_from_master'       => $this->latestPhpVersion['52'],
            'mysqli_enable_rpl_parse'               => $this->latestPhpVersion['52'],
            'mysqli_fetch'                          => $this->latestPhpVersion['53'],
            'mysqli_get_metadata'                   => $this->latestPhpVersion['53'],
            'mysqli_master_query'                   => $this->latestPhpVersion['52'],
            'mysqli_param_count'                    => $this->latestPhpVersion['53'],
            'mysqli_rpl_parse_enabled'              => $this->latestPhpVersion['52'],
            'mysqli_rpl_probe'                      => $this->latestPhpVersion['52'],
            'mysqli_rpl_query_type'                 => $this->latestPhpVersion['52'],
            'mysqli_send_long_data'                 => $this->latestPhpVersion['53'],
            'mysqli_send_query'                     => $this->latestPhpVersion['52'],
            'mysqli_slave_query'                    => $this->latestPhpVersion['52'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mysqli';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'MYSQLI_RPL_ADMIN'                      => $this->latestPhpVersion['52'],
            'MYSQLI_RPL_MASTER'                     => $this->latestPhpVersion['52'],
            'MYSQLI_RPL_SLAVE'                      => $this->latestPhpVersion['52'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Oauth';
        $ext     = 'methods';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'OAuth::__destruct'                     => $this->latestPhpVersion['56'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Oci8';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'oci_internal_debug'                    => $this->latestPhpVersion['74'],
            'ociinternaldebug'                      => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Oci8';
        $ext     = 'classes';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'OCI-Collection'                        => $this->latestPhpVersion['74'],
            'OCI-Lob'                               => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Oci8';
        $ext     = 'methods';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'OCI-Collection::append'                => $this->latestPhpVersion['74'],
            'OCI-Collection::assign'                => $this->latestPhpVersion['74'],
            'OCI-Collection::assignelem'            => $this->latestPhpVersion['74'],
            'OCI-Collection::free'                  => $this->latestPhpVersion['74'],
            'OCI-Collection::getelem'               => $this->latestPhpVersion['74'],
            'OCI-Collection::max'                   => $this->latestPhpVersion['74'],
            'OCI-Collection::size'                  => $this->latestPhpVersion['74'],
            'OCI-Collection::trim'                  => $this->latestPhpVersion['74'],
            'OCI-Lob::append'                       => $this->latestPhpVersion['74'],
            'OCI-Lob::close'                        => $this->latestPhpVersion['74'],
            'OCI-Lob::eof'                          => $this->latestPhpVersion['74'],
            'OCI-Lob::erase'                        => $this->latestPhpVersion['74'],
            'OCI-Lob::export'                       => $this->latestPhpVersion['74'],
            'OCI-Lob::flush'                        => $this->latestPhpVersion['74'],
            'OCI-Lob::free'                         => $this->latestPhpVersion['74'],
            'OCI-Lob::getbuffering'                 => $this->latestPhpVersion['74'],
            'OCI-Lob::import'                       => $this->latestPhpVersion['74'],
            'OCI-Lob::load'                         => $this->latestPhpVersion['74'],
            'OCI-Lob::read'                         => $this->latestPhpVersion['74'],
            'OCI-Lob::rewind'                       => $this->latestPhpVersion['74'],
            'OCI-Lob::save'                         => $this->latestPhpVersion['74'],
            'OCI-Lob::savefile'                     => $this->latestPhpVersion['74'],
            'OCI-Lob::seek'                         => $this->latestPhpVersion['74'],
            'OCI-Lob::setbuffering'                 => $this->latestPhpVersion['74'],
            'OCI-Lob::size'                         => $this->latestPhpVersion['74'],
            'OCI-Lob::tell'                         => $this->latestPhpVersion['74'],
            'OCI-Lob::truncate'                     => $this->latestPhpVersion['74'],
            'OCI-Lob::write'                        => $this->latestPhpVersion['74'],
            'OCI-Lob::writetemporary'               => $this->latestPhpVersion['74'],
            'OCI-Lob::writetofile'                  => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Reflection';
        $ext     = 'methods';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'Reflection::export'                    => $this->latestPhpVersion['74'],
            'ReflectionClass::export'               => $this->latestPhpVersion['74'],
            'ReflectionExtension::export'           => $this->latestPhpVersion['74'],
            'ReflectionFunction::export'            => $this->latestPhpVersion['74'],
            'ReflectionMethod::export'              => $this->latestPhpVersion['74'],
            'ReflectionParameter::export'           => $this->latestPhpVersion['74'],
            'ReflectionProperty::export'            => $this->latestPhpVersion['74'],
            'ReflectionZendExtension::export'       => $this->latestPhpVersion['74'],
            'Reflector::export'                     => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Reflection';
        $ext     = 'methods';
        $major   = '70';
        $entry   = 'php_max';
        $names   = array(
            'ReflectionType::isBuiltin'             => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Reflection';
        $ext     = 'methods';
        $major   = '71';
        $entry   = 'php_max';
        $names   = array(
            'ReflectionClassConstant::export'       => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Session';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'session_is_registered'                 => $this->latestPhpVersion['53'],
            'session_register'                      => $this->latestPhpVersion['53'],
            'session_unregister'                    => $this->latestPhpVersion['53'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Session';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'session.entropy_file'                  => $this->latestPhpVersion['70'],
            'session.entropy_length'                => $this->latestPhpVersion['70'],
            'session.hash_function'                 => $this->latestPhpVersion['70'],
            'session.hash_bits_per_character'       => $this->latestPhpVersion['70'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Soap';
        $ext     = 'methods';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'SoapClient::SoapClient'                => $this->latestPhpVersion['74'],
            'SoapFault::SoapFault'                  => $this->latestPhpVersion['74'],
            'SoapHeader::SoapHeader'                => $this->latestPhpVersion['74'],
            'SoapParam::SoapParam'                  => $this->latestPhpVersion['74'],
            'SoapServer::SoapServer'                => $this->latestPhpVersion['74'],
            'SoapVar::SoapVar'                      => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Spl';
        $ext     = 'interfaces';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'ArrayAccess'                           => $this->latestPhpVersion['52'],
            'Iterator'                              => $this->latestPhpVersion['52'],
            'IteratorAggregate'                     => $this->latestPhpVersion['52'],
            'Serializable'                          => $this->latestPhpVersion['52'],
            'Traversable'                           => $this->latestPhpVersion['52'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Spl';
        $ext     = 'classes';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'SimpleXMLIterator'                     => $this->latestPhpVersion['52'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Spl';
        $ext     = 'methods';
        $major   = '5';
        $entry   = 'ext_max';
        $names   = array(
            'SplFileObject::fgetss'                 => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Standard';
        $ext     = 'iniEntries';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'safe_mode_allowed_env_vars'            => $this->latestPhpVersion['53'],
            'safe_mode_protected_env_vars'          => $this->latestPhpVersion['53'],

            'assert.quiet_eval'                     => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Standard';
        $ext     = 'functions';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'define_syslog_variables'               => $this->latestPhpVersion['53'],
            'php_logo_guid'                         => $this->latestPhpVersion['54'],
            'php_real_logo_guid'                    => $this->latestPhpVersion['54'],
            'zend_logo_guid'                        => $this->latestPhpVersion['54'],
            'php_egg_logo_guid'                     => $this->latestPhpVersion['54'],
            'import_request_variables'              => $this->latestPhpVersion['53'],

            'call_user_method'                      => $this->latestPhpVersion['56'],
            'call_user_method_array'                => $this->latestPhpVersion['56'],
            'magic_quotes_runtime'                  => $this->latestPhpVersion['56'],
            'set_magic_quotes_runtime'              => $this->latestPhpVersion['56'],
            'set_socket_blocking'                   => $this->latestPhpVersion['56'],

            'convert_cyr_string'                    => $this->latestPhpVersion['74'],
            'ezmlm_hash'                            => $this->latestPhpVersion['74'],
            'fgetss'                                => $this->latestPhpVersion['74'],
            'get_magic_quotes_gpc'                  => $this->latestPhpVersion['74'],
            'get_magic_quotes_runtime'              => $this->latestPhpVersion['74'],
            'hebrevc'                               => $this->latestPhpVersion['74'],
            'is_real'                               => $this->latestPhpVersion['74'],
            'money_format'                          => $this->latestPhpVersion['74'],
            'restore_include_path'                  => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Standard';
        $ext     = 'constants';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'STREAM_ENFORCE_SAFE_MODE'              => $this->latestPhpVersion['53'],

            'ASSERT_QUIET_EVAL'                     => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Tidy';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'ob_tidyhandler'                        => $this->latestPhpVersion['53'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Tokenizer';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'T_BAD_CHARACTER'                       => $this->latestPhpVersion['56'],
            'T_CHARACTER'                           => $this->latestPhpVersion['56'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Xdebug';
        $ext     = 'iniEntries';
        $major   = '2';
        $entry   = 'php_max';
        $names   = array(
            'xdebug.remote_addr_header'             => $this->latestPhpVersion['74'],
            'xdebug.remote_cookie_expire_time'      => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Xdebug';
        $ext     = 'functions';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            'xdebug_disable'                        => $this->latestPhpVersion['74'],
            'xdebug_enable'                         => $this->latestPhpVersion['74'],
            'xdebug_is_enabled'                     => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Xdebug';
        $ext     = 'functions';
        $major   = '2';
        $entry   = 'php_max';
        $names   = array(
            'xdebug_get_declared_vars'              => $this->latestPhpVersion['74'],
            'xdebug_get_formatted_function_stack'   => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Xdebug';
        $ext     = 'constants';
        $major   = '2';
        $entry   = 'php_max';
        $names   = array(
            'XDEBUG_CC_BRANCH_CHECK'                => $this->latestPhpVersion['74'],
            'XDEBUG_CC_DEAD_CODE'                   => $this->latestPhpVersion['74'],
            'XDEBUG_CC_UNUSED'                      => $this->latestPhpVersion['74'],
            'XDEBUG_NAMESPACE_BLACKLIST'            => $this->latestPhpVersion['74'],
            'XDEBUG_NAMESPACE_WHITELIST'            => $this->latestPhpVersion['74'],
            'XDEBUG_PATH_BLACKLIST'                 => $this->latestPhpVersion['74'],
            'XDEBUG_PATH_WHITELIST'                 => $this->latestPhpVersion['74'],
            'XDEBUG_TRACE_APPEND'                   => $this->latestPhpVersion['74'],
            'XDEBUG_TRACE_COMPUTERIZED'             => $this->latestPhpVersion['74'],
            'XDEBUG_TRACE_HTML'                     => $this->latestPhpVersion['74'],
            'XDEBUG_TRACE_NAKED_FILENAME'           => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Xsl';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'xsl.security_prefs'                    => $this->latestPhpVersion['56'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'opcache';
        $ext     = 'iniEntries';
        $major   = '7';
        $entry   = 'php_max';
        $names   = array(
            'opcache.load_comments'                 => $this->latestPhpVersion['56'],

            'opcache.fast_shutdown'                 => $this->latestPhpVersion['71'],

            'opcache.inherited_hack'                => $this->latestPhpVersion['72'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Zlib';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'gzgetss'                               => $this->latestPhpVersion['74'],
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        // tag MAX version
        while (!empty($latest)) {
            list($refName, $ext, $major, $entry, $names) = array_pop($latest);

            $data = $this->jsonFileHandler->read($refName, $ext, $major);

            if (!$data) {
                if (json_last_error() == JSON_ERROR_NONE) {
                    $error = sprintf('File %s.%s.json does not exist.', $refName, $ext);
                } else {
                    $error = sprintf('Cannot decode file %s.%s.json', $refName, $ext);
                }
                throw new RuntimeException($error);
            }

            $key = $ext == 'releases' ? 'rel_version' : 'name';

            if ('methods' === $ext) {
                $methods = [];
                foreach (array_keys($names) as $method) {
                    $parts = explode ('::', $method);
                    if (!isset($methods[$parts[0]])) {
                        $methods[$parts[0]] = [];
                    }
                    $methods[$parts[0]][] = $parts[1];
                }
            }

            foreach ($data as &$element) {
                if ('methods' === $ext) {
                    if (array_key_exists($element['class_name'], $methods)) {
                        if (in_array($element[$key], $methods[$element['class_name']])) {
                            $element[$entry] = $names[implode('::', [$element['class_name'], $element[$key]])];
                        }
                    }
                } else {
                    if (array_key_exists($element[$key], $names)) {
                        $element[$entry] = $names[$element[$key]];
                    } elseif (array_key_exists('*', $names)) {
                        $element[$entry] = $names['*'];
                    }
                }
            }
            $this->jsonFileHandler->write($refName, $ext, $major, $data);
        }
    }
}

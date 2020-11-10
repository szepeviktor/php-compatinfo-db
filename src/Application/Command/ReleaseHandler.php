<?php

declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Application\Command;

use Bartlett\CompatInfoDb\Application\Service\JsonFileHandler;
use Bartlett\CompatInfoDb\ExtensionFactory;

class ReleaseHandler implements CommandHandlerInterface
{
    private $jsonFileHandler;

    public function __construct(JsonFileHandler $jsonFileHandler)
    {
        $this->jsonFileHandler = $jsonFileHandler;
    }

    public function __invoke(ReleaseCommand $command): void
    {
        $latest  = array();

        $refName = 'Curl';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'CURLCLOSEPOLICY_CALLBACK'              => ExtensionFactory::LATEST_PHP_5_5,
            'CURLCLOSEPOLICY_LEAST_RECENTLY_USED'   => ExtensionFactory::LATEST_PHP_5_5,
            'CURLCLOSEPOLICY_LEAST_TRAFFIC'         => ExtensionFactory::LATEST_PHP_5_5,
            'CURLCLOSEPOLICY_OLDEST'                => ExtensionFactory::LATEST_PHP_5_5,
            'CURLCLOSEPOLICY_SLOWEST'               => ExtensionFactory::LATEST_PHP_5_5,
            'CURLOPT_CLOSEPOLICY'                   => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Core';
        $ext     = 'iniEntries';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'allow_call_time_pass_reference'        => ExtensionFactory::LATEST_PHP_5_3,
            'define_syslog_variables'               => ExtensionFactory::LATEST_PHP_5_3,
            'highlight.bg'                          => ExtensionFactory::LATEST_PHP_5_3,
            'magic_quotes_gpc'                      => ExtensionFactory::LATEST_PHP_5_3,
            'magic_quotes_runtime'                  => ExtensionFactory::LATEST_PHP_5_3,
            'magic_quotes_sybase'                   => ExtensionFactory::LATEST_PHP_5_3,
            'register_globals'                      => ExtensionFactory::LATEST_PHP_5_3,
            'safe_mode'                             => ExtensionFactory::LATEST_PHP_5_3,
            'safe_mode_exec_dir'                    => ExtensionFactory::LATEST_PHP_5_3,
            'y2k_compliance'                        => ExtensionFactory::LATEST_PHP_5_3,
            'safe_mode_gid'                         => ExtensionFactory::LATEST_PHP_5_3,
            'safe_mode_include_dir'                 => ExtensionFactory::LATEST_PHP_5_3,

            'always_populate_raw_post_data'         => ExtensionFactory::LATEST_PHP_5_6,
            'asp_tags'                              => ExtensionFactory::LATEST_PHP_5_6,

            'exit_on_timeout'                       => ExtensionFactory::LATEST_PHP_7_0,

            'sql.safe_mode'                         => ExtensionFactory::LATEST_PHP_7_1,

            'track_errors'                          => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Core';
        $ext     = 'iniEntries';
        $major   = '5';
        $entry   = 'php_max';
        $names   = array(
            'register_long_arrays'                  => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Core';
        $ext     = 'functions';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'create_function'                       => ExtensionFactory::LATEST_PHP_7_4,
            'each'                                  => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Core';
        $ext     = 'constants';
        $major   = '5';
        $entry   = 'php_max';
        $names   = array(
            'ZEND_MULTIBYTE'                        => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Dom';
        $ext     = 'classes';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'DOMConfiguration'                      => ExtensionFactory::LATEST_PHP_7_4,
            'DOMDomError'                           => ExtensionFactory::LATEST_PHP_7_4,
            'DOMErrorHandler'                       => ExtensionFactory::LATEST_PHP_7_4,
            'DOMImplementationList'                 => ExtensionFactory::LATEST_PHP_7_4,
            'DOMImplementationSource'               => ExtensionFactory::LATEST_PHP_7_4,
            'DOMLocator'                            => ExtensionFactory::LATEST_PHP_7_4,
            'DOMNameList'                           => ExtensionFactory::LATEST_PHP_7_4,
            'DOMStringExtend'                       => ExtensionFactory::LATEST_PHP_7_4,
            'DOMStringList'                         => ExtensionFactory::LATEST_PHP_7_4,
            'DOMTypeinfo'                           => ExtensionFactory::LATEST_PHP_7_4,
            'DOMUserDataHandler'                    => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Dom';
        $ext     = 'methods';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'DOMConfiguration::canSetParameter'     => ExtensionFactory::LATEST_PHP_7_4,
            'DOMConfiguration::getParameter'        => ExtensionFactory::LATEST_PHP_7_4,
            'DOMConfiguration::setParameter'        => ExtensionFactory::LATEST_PHP_7_4,
            'DOMDocument::renameNode'               => ExtensionFactory::LATEST_PHP_7_4,
            'DOMErrorHandler::handleError'          => ExtensionFactory::LATEST_PHP_7_4,
            'DOMImplementationList::item'           => ExtensionFactory::LATEST_PHP_7_4,
            'DOMImplementationSource::getDomimplementation'  => ExtensionFactory::LATEST_PHP_7_4,
            'DOMImplementationSource::getDomimplementations' => ExtensionFactory::LATEST_PHP_7_4,
            'DOMNameList::getName'                  => ExtensionFactory::LATEST_PHP_7_4,
            'DOMNameList::getNamespaceURI'          => ExtensionFactory::LATEST_PHP_7_4,
            'DOMNamedNodeMap::removeNamedItem'      => ExtensionFactory::LATEST_PHP_7_4,
            'DOMNamedNodeMap::removeNamedItemNS'    => ExtensionFactory::LATEST_PHP_7_4,
            'DOMNamedNodeMap::setNamedItem'         => ExtensionFactory::LATEST_PHP_7_4,
            'DOMNamedNodeMap::setNamedItemNS'       => ExtensionFactory::LATEST_PHP_7_4,
            'DOMNode::compareDocumentPosition'      => ExtensionFactory::LATEST_PHP_7_4,
            'DOMNode::getFeature'                   => ExtensionFactory::LATEST_PHP_7_4,
            'DOMNode::getUserData'                  => ExtensionFactory::LATEST_PHP_7_4,
            'DOMNode::isEqualNode'                  => ExtensionFactory::LATEST_PHP_7_4,
            'DOMNode::setUserData'                  => ExtensionFactory::LATEST_PHP_7_4,
            'DOMStringExtend::findOffset16'         => ExtensionFactory::LATEST_PHP_7_4,
            'DOMStringExtend::findOffset32'         => ExtensionFactory::LATEST_PHP_7_4,
            'DOMStringList::item'                   => ExtensionFactory::LATEST_PHP_7_4,
            'DOMText::replaceWholeText'             => ExtensionFactory::LATEST_PHP_7_4,
            'DOMUserDataHandler::handle'            => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Exif';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'read_exif_data'                        => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Fileinfo';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'FILEINFO_COMPRESS'                     => ExtensionFactory::LATEST_PHP_5_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Fileinfo';
        $ext     = 'methods';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'finfo::finfo'                          => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Filter';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'FILTER_FLAG_HOST_REQUIRED'             => ExtensionFactory::LATEST_PHP_7_4,
            'FILTER_FLAG_SCHEME_REQUIRED'           => ExtensionFactory::LATEST_PHP_7_4,
            'FILTER_SANITIZE_MAGIC_QUOTES'          => ExtensionFactory::LATEST_PHP_7_4,
            'INPUT_REQUEST'                         => ExtensionFactory::LATEST_PHP_7_4,
            'INPUT_SESSION'                         => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Gd';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'image2wbmp'                            => ExtensionFactory::LATEST_PHP_7_4,
            'jpeg2wbmp'                             => ExtensionFactory::LATEST_PHP_7_4,
            'png2wbmp'                              => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Gmp';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'gmp_random'                            => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'releases';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '0.7.0'                                 => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'releases';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            '1.0.0'                                 => ExtensionFactory::LATEST_PHP_5_5,
            '1.3.0'                                 => ExtensionFactory::LATEST_PHP_5_5,
            '1.5.0'                                 => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'classes';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'HttpRequest'                           => ExtensionFactory::LATEST_PHP_5_5,
            'HttpResponse'                          => ExtensionFactory::LATEST_PHP_5_5,
            'HttpUtil'                              => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'classes';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            'HttpDeflateStream'                     => ExtensionFactory::LATEST_PHP_5_5,
            'HttpEncodingException'                 => ExtensionFactory::LATEST_PHP_5_5,
            'HttpException'                         => ExtensionFactory::LATEST_PHP_5_5,
            'HttpHeaderException'                   => ExtensionFactory::LATEST_PHP_5_5,
            'HttpInflateStream'                     => ExtensionFactory::LATEST_PHP_5_5,
            'HttpInvalidParamException'             => ExtensionFactory::LATEST_PHP_5_5,
            'HttpMalformedHeadersException'         => ExtensionFactory::LATEST_PHP_5_5,
            'HttpMessage'                           => ExtensionFactory::LATEST_PHP_5_5,
            'HttpMessageTypeException'              => ExtensionFactory::LATEST_PHP_5_5,
            'HttpQueryString'                       => ExtensionFactory::LATEST_PHP_5_5,
            'HttpQueryStringException'              => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRequestException'                  => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRequestMethodException'            => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRequestPool'                       => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRequestPoolException'              => ExtensionFactory::LATEST_PHP_5_5,
            'HttpResponseException'                 => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRuntimeException'                  => ExtensionFactory::LATEST_PHP_5_5,
            'HttpSocketException'                   => ExtensionFactory::LATEST_PHP_5_5,
            'HttpUrlException'                      => ExtensionFactory::LATEST_PHP_5_5,
            'HttpRequestDataShare'                  => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '*'                                     => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '*'                                     => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Http';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            '*'                                     => ExtensionFactory::LATEST_PHP_5_5,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Iconv';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'ob_iconv_handler'                      => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Intl';
        $ext     = 'functions';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            'datefmt_set_timezone_id'               => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Intl';
        $ext     = 'methods';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            'IntlDateFormatter::setTimeZoneId'      => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Intl';
        $ext     = 'constants';
        $major   = '2';
        $entry   = 'php_max';
        $names   = array(
            'INTL_IDNA_VARIANT_2003'                => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Ldap';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'ldap_control_paged_result'             => ExtensionFactory::LATEST_PHP_7_4,
            'ldap_control_paged_result_response'    => ExtensionFactory::LATEST_PHP_7_4,
            'ldap_sort'                             => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mbstring';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'mbstring.func_overload'                => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mbstring';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'mbereg'                                => ExtensionFactory::LATEST_PHP_7_4,
            'mbereg_match'                          => ExtensionFactory::LATEST_PHP_7_4,
            'mbereg_replace'                        => ExtensionFactory::LATEST_PHP_7_4,
            'mbereg_search'                         => ExtensionFactory::LATEST_PHP_7_4,
            'mbereg_search_getpos'                  => ExtensionFactory::LATEST_PHP_7_4,
            'mbereg_search_getregs'                 => ExtensionFactory::LATEST_PHP_7_4,
            'mbereg_search_init'                    => ExtensionFactory::LATEST_PHP_7_4,
            'mbereg_search_pos'                     => ExtensionFactory::LATEST_PHP_7_4,
            'mbereg_search_regs'                    => ExtensionFactory::LATEST_PHP_7_4,
            'mbereg_search_setpos'                  => ExtensionFactory::LATEST_PHP_7_4,
            'mberegi'                               => ExtensionFactory::LATEST_PHP_7_4,
            'mberegi_replace'                       => ExtensionFactory::LATEST_PHP_7_4,
            'mbregex_encoding'                      => ExtensionFactory::LATEST_PHP_7_4,
            'mbsplit'                               => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mbstring';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'MB_OVERLOAD_MAIL'                      => ExtensionFactory::LATEST_PHP_7_4,
            'MB_OVERLOAD_REGEX'                     => ExtensionFactory::LATEST_PHP_7_4,
            'MB_OVERLOAD_STRING'                    => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mcrypt';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'MCRYPT_3DES'                           => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_ARCFOUR'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_ARCFOUR_IV'                     => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_BLOWFISH'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_CAST_128'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_CAST_256'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_CRYPT'                          => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_DECRYPT'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_DES'                            => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_DES_COMPAT'                     => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_DEV_RANDOM'                     => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_DEV_URANDOM'                    => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_ENCRYPT'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_ENIGNA'                         => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_GOST'                           => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_IDEA'                           => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_LOKI97'                         => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_MARS'                           => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_MODE_CBC'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_MODE_CFB'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_MODE_ECB'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_MODE_NOFB'                      => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_MODE_OFB'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_MODE_STREAM'                    => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_PANAMA'                         => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RAND'                           => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RC2'                            => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RC4'                            => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RC6'                            => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RC6_128'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RC6_192'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RC6_256'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RIJNDAEL_128'                   => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RIJNDAEL_192'                   => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_RIJNDAEL_256'                   => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SAFER128'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SAFER64'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SAFERPLUS'                      => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SERPENT'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SERPENT_128'                    => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SERPENT_192'                    => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SERPENT_256'                    => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_SKIPJACK'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_TEAN'                           => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_THREEWAY'                       => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_TRIPLEDES'                      => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_TWOFISH'                        => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_TWOFISH128'                     => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_TWOFISH192'                     => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_TWOFISH256'                     => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_WAKE'                           => ExtensionFactory::LATEST_PHP_7_1,
            'MCRYPT_XTEA'                           => ExtensionFactory::LATEST_PHP_7_1,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);
        $entry   = 'php_max';
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mcrypt';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'mcrypt_cbc'                            => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_cfb'                            => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_create_iv'                      => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_decrypt'                        => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_ecb'                            => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_get_algorithms_name'        => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_get_block_size'             => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_get_iv_size'                => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_get_key_size'               => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_get_modes_name'             => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_get_supported_key_sizes'    => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_is_block_algorithm'         => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_is_block_algorithm_mode'    => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_is_block_mode'              => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_enc_self_test'                  => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_encrypt'                        => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_generic'                        => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_generic_deinit'                 => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_generic_end'                    => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_generic_init'                   => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_get_block_size'                 => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_get_cipher_name'                => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_get_iv_size'                    => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_get_key_size'                   => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_list_algorithms'                => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_list_modes'                     => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_close'                   => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_get_algo_block_size'     => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_get_algo_key_size'       => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_get_supported_key_sizes' => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_is_block_algorithm'      => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_is_block_algorithm_mode' => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_is_block_mode'           => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_open'                    => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_module_self_test'               => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt_ofb'                            => ExtensionFactory::LATEST_PHP_7_1,
            'mdecrypt_generic'                      => ExtensionFactory::LATEST_PHP_7_1,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);
        $entry   = 'php_max';
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mcrypt';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'mcrypt.algorithms_dir'                 => ExtensionFactory::LATEST_PHP_7_1,
            'mcrypt.modes_dir'                      => ExtensionFactory::LATEST_PHP_7_1,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);
        $entry   = 'php_max';
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mcrypt';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'mcrypt_ecb'                            => ExtensionFactory::LATEST_PHP_5_6,
            'mcrypt_cbc'                            => ExtensionFactory::LATEST_PHP_5_6,
            'mcrypt_cfb'                            => ExtensionFactory::LATEST_PHP_5_6,
            'mcrypt_ofb'                            => ExtensionFactory::LATEST_PHP_5_6,
            'mcrypt_generic_end'                    => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Memcached';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'memcached.sess_binary'                 => ExtensionFactory::LATEST_PHP_7_4,
            'memcached.sess_remove_failed'          => ExtensionFactory::LATEST_PHP_7_4,
            'memcached.use_sasl'                    => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mysqli';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'mysqli_bind_param'                     => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_bind_result'                    => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_client_encoding'                => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_disable_reads_from_master'      => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_disable_rpl_parse'              => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_enable_reads_from_master'       => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_enable_rpl_parse'               => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_fetch'                          => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_get_metadata'                   => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_master_query'                   => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_param_count'                    => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_rpl_parse_enabled'              => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_rpl_probe'                      => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_rpl_query_type'                 => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_send_long_data'                 => ExtensionFactory::LATEST_PHP_5_3,
            'mysqli_send_query'                     => ExtensionFactory::LATEST_PHP_5_2,
            'mysqli_slave_query'                    => ExtensionFactory::LATEST_PHP_5_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Mysqli';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'MYSQLI_RPL_ADMIN'                      => ExtensionFactory::LATEST_PHP_5_2,
            'MYSQLI_RPL_MASTER'                     => ExtensionFactory::LATEST_PHP_5_2,
            'MYSQLI_RPL_SLAVE'                      => ExtensionFactory::LATEST_PHP_5_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Oauth';
        $ext     = 'methods';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'OAuth::__destruct'                     => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Oci8';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'oci_internal_debug'                    => ExtensionFactory::LATEST_PHP_7_4,
            'ociinternaldebug'                      => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Oci8';
        $ext     = 'classes';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'OCI-Collection'                        => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob'                               => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Oci8';
        $ext     = 'methods';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'OCI-Collection::append'                => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Collection::assign'                => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Collection::assignelem'            => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Collection::free'                  => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Collection::getelem'               => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Collection::max'                   => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Collection::size'                  => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Collection::trim'                  => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::append'                       => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::close'                        => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::eof'                          => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::erase'                        => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::export'                       => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::flush'                        => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::free'                         => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::getbuffering'                 => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::import'                       => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::load'                         => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::read'                         => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::rewind'                       => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::save'                         => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::savefile'                     => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::seek'                         => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::setbuffering'                 => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::size'                         => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::tell'                         => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::truncate'                     => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::write'                        => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::writetemporary'               => ExtensionFactory::LATEST_PHP_7_4,
            'OCI-Lob::writetofile'                  => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Reflection';
        $ext     = 'methods';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'Reflection::export'                    => ExtensionFactory::LATEST_PHP_7_4,
            'ReflectionClass::export'               => ExtensionFactory::LATEST_PHP_7_4,
            'ReflectionExtension::export'           => ExtensionFactory::LATEST_PHP_7_4,
            'ReflectionFunction::export'            => ExtensionFactory::LATEST_PHP_7_4,
            'ReflectionMethod::export'              => ExtensionFactory::LATEST_PHP_7_4,
            'ReflectionParameter::export'           => ExtensionFactory::LATEST_PHP_7_4,
            'ReflectionProperty::export'            => ExtensionFactory::LATEST_PHP_7_4,
            'ReflectionZendExtension::export'       => ExtensionFactory::LATEST_PHP_7_4,
            'Reflector::export'                     => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Reflection';
        $ext     = 'methods';
        $major   = '70';
        $entry   = 'php_max';
        $names   = array(
            'ReflectionType::isBuiltin'             => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Reflection';
        $ext     = 'methods';
        $major   = '71';
        $entry   = 'php_max';
        $names   = array(
            'ReflectionClassConstant::export'       => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Session';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'session_is_registered'                 => ExtensionFactory::LATEST_PHP_5_3,
            'session_register'                      => ExtensionFactory::LATEST_PHP_5_3,
            'session_unregister'                    => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Session';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'session.entropy_file'                  => ExtensionFactory::LATEST_PHP_7_0,
            'session.entropy_length'                => ExtensionFactory::LATEST_PHP_7_0,
            'session.hash_function'                 => ExtensionFactory::LATEST_PHP_7_0,
            'session.hash_bits_per_character'       => ExtensionFactory::LATEST_PHP_7_0,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Soap';
        $ext     = 'methods';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'SoapClient::SoapClient'                => ExtensionFactory::LATEST_PHP_7_4,
            'SoapFault::SoapFault'                  => ExtensionFactory::LATEST_PHP_7_4,
            'SoapHeader::SoapHeader'                => ExtensionFactory::LATEST_PHP_7_4,
            'SoapParam::SoapParam'                  => ExtensionFactory::LATEST_PHP_7_4,
            'SoapServer::SoapServer'                => ExtensionFactory::LATEST_PHP_7_4,
            'SoapVar::SoapVar'                      => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Spl';
        $ext     = 'interfaces';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'ArrayAccess'                           => ExtensionFactory::LATEST_PHP_5_2,
            'Iterator'                              => ExtensionFactory::LATEST_PHP_5_2,
            'IteratorAggregate'                     => ExtensionFactory::LATEST_PHP_5_2,
            'Serializable'                          => ExtensionFactory::LATEST_PHP_5_2,
            'Traversable'                           => ExtensionFactory::LATEST_PHP_5_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Spl';
        $ext     = 'classes';
        $major   = '';
        $entry   = 'ext_max';
        $names   = array(
            'SimpleXMLIterator'                     => ExtensionFactory::LATEST_PHP_5_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Spl';
        $ext     = 'methods';
        $major   = '5';
        $entry   = 'ext_max';
        $names   = array(
            'SplFileObject::fgetss'                 => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Standard';
        $ext     = 'iniEntries';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'safe_mode_allowed_env_vars'            => ExtensionFactory::LATEST_PHP_5_3,
            'safe_mode_protected_env_vars'          => ExtensionFactory::LATEST_PHP_5_3,

            'assert.quiet_eval'                     => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Standard';
        $ext     = 'functions';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'define_syslog_variables'               => ExtensionFactory::LATEST_PHP_5_3,
            'php_logo_guid'                         => ExtensionFactory::LATEST_PHP_5_4,
            'php_real_logo_guid'                    => ExtensionFactory::LATEST_PHP_5_4,
            'zend_logo_guid'                        => ExtensionFactory::LATEST_PHP_5_4,
            'php_egg_logo_guid'                     => ExtensionFactory::LATEST_PHP_5_4,
            'import_request_variables'              => ExtensionFactory::LATEST_PHP_5_3,

            'call_user_method'                      => ExtensionFactory::LATEST_PHP_5_6,
            'call_user_method_array'                => ExtensionFactory::LATEST_PHP_5_6,
            'magic_quotes_runtime'                  => ExtensionFactory::LATEST_PHP_5_6,
            'set_magic_quotes_runtime'              => ExtensionFactory::LATEST_PHP_5_6,
            'set_socket_blocking'                   => ExtensionFactory::LATEST_PHP_5_6,

            'convert_cyr_string'                    => ExtensionFactory::LATEST_PHP_7_4,
            'ezmlm_hash'                            => ExtensionFactory::LATEST_PHP_7_4,
            'fgetss'                                => ExtensionFactory::LATEST_PHP_7_4,
            'get_magic_quotes_gpc'                  => ExtensionFactory::LATEST_PHP_7_4,
            'get_magic_quotes_runtime'              => ExtensionFactory::LATEST_PHP_7_4,
            'hebrevc'                               => ExtensionFactory::LATEST_PHP_7_4,
            'is_real'                               => ExtensionFactory::LATEST_PHP_7_4,
            'money_format'                          => ExtensionFactory::LATEST_PHP_7_4,
            'restore_include_path'                  => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Standard';
        $ext     = 'constants';
        $major   = '4';
        $entry   = 'php_max';
        $names   = array(
            'STREAM_ENFORCE_SAFE_MODE'              => ExtensionFactory::LATEST_PHP_5_3,

            'ASSERT_QUIET_EVAL'                     => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Tidy';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'ob_tidyhandler'                        => ExtensionFactory::LATEST_PHP_5_3,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Tokenizer';
        $ext     = 'constants';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'T_BAD_CHARACTER'                       => ExtensionFactory::LATEST_PHP_5_6,
            'T_CHARACTER'                           => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Xdebug';
        $ext     = 'iniEntries';
        $major   = '2';
        $entry   = 'php_max';
        $names   = array(
            'xdebug.remote_addr_header'             => ExtensionFactory::LATEST_PHP_7_4,
            'xdebug.remote_cookie_expire_time'      => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Xdebug';
        $ext     = 'functions';
        $major   = '1';
        $entry   = 'php_max';
        $names   = array(
            'xdebug_disable'                        => ExtensionFactory::LATEST_PHP_7_4,
            'xdebug_enable'                         => ExtensionFactory::LATEST_PHP_7_4,
            'xdebug_is_enabled'                     => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Xdebug';
        $ext     = 'functions';
        $major   = '2';
        $entry   = 'php_max';
        $names   = array(
            'xdebug_get_declared_vars'              => ExtensionFactory::LATEST_PHP_7_4,
            'xdebug_get_formatted_function_stack'   => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Xdebug';
        $ext     = 'constants';
        $major   = '2';
        $entry   = 'php_max';
        $names   = array(
            'XDEBUG_CC_BRANCH_CHECK'                => ExtensionFactory::LATEST_PHP_7_4,
            'XDEBUG_CC_DEAD_CODE'                   => ExtensionFactory::LATEST_PHP_7_4,
            'XDEBUG_CC_UNUSED'                      => ExtensionFactory::LATEST_PHP_7_4,
            'XDEBUG_NAMESPACE_BLACKLIST'            => ExtensionFactory::LATEST_PHP_7_4,
            'XDEBUG_NAMESPACE_WHITELIST'            => ExtensionFactory::LATEST_PHP_7_4,
            'XDEBUG_PATH_BLACKLIST'                 => ExtensionFactory::LATEST_PHP_7_4,
            'XDEBUG_PATH_WHITELIST'                 => ExtensionFactory::LATEST_PHP_7_4,
            'XDEBUG_TRACE_APPEND'                   => ExtensionFactory::LATEST_PHP_7_4,
            'XDEBUG_TRACE_COMPUTERIZED'             => ExtensionFactory::LATEST_PHP_7_4,
            'XDEBUG_TRACE_HTML'                     => ExtensionFactory::LATEST_PHP_7_4,
            'XDEBUG_TRACE_NAKED_FILENAME'           => ExtensionFactory::LATEST_PHP_7_4,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Xsl';
        $ext     = 'iniEntries';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'xsl.security_prefs'                    => ExtensionFactory::LATEST_PHP_5_6,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'opcache';
        $ext     = 'iniEntries';
        $major   = '7';
        $entry   = 'php_max';
        $names   = array(
            'opcache.load_comments'                 => ExtensionFactory::LATEST_PHP_5_6,

            'opcache.fast_shutdown'                 => ExtensionFactory::LATEST_PHP_7_1,

            'opcache.inherited_hack'                => ExtensionFactory::LATEST_PHP_7_2,
        );
        $latest[] = array($refName, $ext, $major, $entry, $names);

        $refName = 'Zlib';
        $ext     = 'functions';
        $major   = '';
        $entry   = 'php_max';
        $names   = array(
            'gzgetss'                               => ExtensionFactory::LATEST_PHP_7_4,
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
                throw new \RuntimeException($error);
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

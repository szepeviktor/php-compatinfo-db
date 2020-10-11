<?php declare(strict_types=1);

namespace Bartlett\CompatInfoDb\Infrastructure;

use Bartlett\CompatInfoDb\Application\Command\DiagnoseCommand;

use Symfony\Requirements\RequirementCollection;

use PDO;
use PDOException;

/**
 * @since 3.0.0
 */
class ProjectRequirements extends RequirementCollection
{
    const REQUIRED_PHP_VERSION = '7.1.0';

    /** @var string */
    private $pdoStatus;

    public function __construct(DiagnoseCommand $command)
    {
        $installedPhpVersion = phpversion();
        $requiredPhpVersion = self::REQUIRED_PHP_VERSION;

        $this->addRequirement(
            version_compare($installedPhpVersion, $requiredPhpVersion, 'ge'),
            sprintf('PHP version must be at least %s', $requiredPhpVersion),
            sprintf(
                'You are running PHP version "<strong>%s</strong>", but CompatInfoDB needs at least PHP "<strong>%s</strong>" to run.',
                $installedPhpVersion,
                $requiredPhpVersion
            ),
            sprintf('Install PHP %s or newer (installed version is %s)', $requiredPhpVersion, $installedPhpVersion)
        );

        $dbParams = $command->databaseParams;

        $this->addRequirement(
            extension_loaded($dbParams['driver']),
            sprintf('%s extension must be available', $dbParams['driver']),
            sprintf('Install the <strong>%s</strong> extension', $dbParams['driver'])
        );

        if (strpos($dbParams['driver'], 'sqlite')) {
            $this->addRequirement(
                $this->checkDbFile($dbParams['url']),
                sprintf('Check if %s can be reached', $dbParams['url']),
                $this->pdoStatus
            );
        } else {
            list($dsnPrefix, ) = explode('://', $dbParams['url']);
            $dsn = sprintf(
                '%s:host=%s;port=%d;dbname=%s',
                $dsnPrefix,
                $dbParams['host'],
                $dbParams['port'],
                $dbParams['dbname']
            );

            $this->addRequirement(
                $this->checkPDO($dsn, $dbParams['user'], $dbParams['password']),
                sprintf('Check if %s can be reached', $dsn),
                $this->pdoStatus
            );
        }
    }

    /**
     * Returns the PHP configuration file (php.ini) path.
     *
     * @return false|string php.ini file path
     */
    public function getPhpIniPath()
    {
        return get_cfg_var('cfg_file_path');
    }

    private function checkDbFile(string $url): bool
    {
        $path = str_replace('sqlite:', '', $url);

        if (is_file($path) && is_readable($path)) {
            $this->pdoStatus = sprintf('DB file %s seems good', $path);
            return true;
        }

        $this->pdoStatus = sprintf('Something is wrong with DB file %s', $path);
        return false;
    }

    private function checkPDO(string $dsn, string $username, string $password, int $timeout = 1): bool
    {
        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => $timeout,
            ];

            $pdo = new PDO($this->dsn, $this->username, $this->password, $options);

            $status = $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
            if (null === $status) {
                $this->pdoStatus = 'Could not talk to database server';
                return false;
            }
            $this->pdoStatus = 'Connection to database server was successful';
            return true;
        } catch (PDOException $e) {
            // skip to failure
            $this->pdoStatus = 'Could not talk to database server, e: ' . $e->getCode();
        }
        return false;
    }
}

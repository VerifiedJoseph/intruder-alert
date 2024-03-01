<?php

namespace IntruderAlert;

use IntruderAlert\Exception\ConfigException;
use GeoIp2\Database\Reader;
use MaxMind\Db\Reader\InvalidDatabaseException;

class Config
{
    /** @var string $minPhpVersion Minimum PHP version */
    private string $minPhpVersion = '8.1.0';

    /** @var array<int, string> $extensions Required PHP extensions */
    private static array $extensions = ['curl', 'json', 'phar', 'pcre'];

    private string $path = '';

    private string $envPrefix = 'IA_';

    /** @var array<string, string> $defaultGeoLite2Paths Default GeoLite2 database paths */
    private array $defaultGeoLite2Paths = [
        'GeoLite2-ASN' => 'data/geoip2/GeoLite2-ASN.mmdb',
        'GeoLite2-Country' => 'data/geoip2/GeoLite2-Country.mmdb'
    ];

    /** @var string $geoIpDatabaseFolder GeoLite2 database folder */
    private string $geoIpDatabaseFolder = 'data/geoip2';

    /** @var string $maxMindDownloadUrl URL for MaxMind GeoIP database downloads */
    private string $maxMindDownloadUrl = 'https://download.maxmind.com/app/geoip_download?';

    /**
     * @throws ConfigException if PHP version not supported.
     * @throws ConfigException if a required PHP extension is not loaded.
     */
    public function __construct()
    {
        if (version_compare(PHP_VERSION, $this->minPhpVersion) === -1) {
            throw new ConfigException('Intruder Alert requires at least PHP version ' . $this->minPhpVersion);
        }

        foreach (self::$extensions as $ext) {
            if (extension_loaded($ext) === false) {
                throw new ConfigException(sprintf('PHP extension error: %s extension not loaded.', $ext));
            }
        }
    }

    /**
     * Set backend directory
     *
     * @param string $path
     */
    public function setDir(string $path): void
    {
        $this->path = $path . DIRECTORY_SEPARATOR;
    }

    /**
     * Get absolute path of a file
     *
     * @param string $file
     */
    public function getPath(string $file = ''): string
    {
        return $this->path . $file;
    }

    public function getVersion(): string
    {
        return (string) constant('VERSION');
    }

    public function getUseragent(): string
    {
        return sprintf(
            'Intruder Alert/%s (+https://github.com/VerifiedJoseph/intruder-alert)',
            $this->getVersion()
        );
    }

    public function getChartsStatus(): bool
    {
        if ($this->getEnv('DASH_CHARTS') === 'false') {
            return false;
        }

        return true;
    }

    public function getDashUpdatesStatus(): bool
    {
        if ($this->getEnv('DASH_UPDATES') === 'false') {
            return false;
        }

        return true;
    }

    public function getDashDaemonLogStatus(): bool
    {
        if ($this->getEnv('DASH_DAEMON_LOG') === 'false') {
            return false;
        }

        return true;
    }

    public function getLogFolder(): string
    {
        return $this->getEnv('LOG_FOLDER');
    }

    public function getLogPaths(): string
    {
        if ($this->hasEnv('LOG_PATHS') === true) {
            return $this->getEnv('LOG_PATHS');
        }

        return '';
    }

    public function getMaxMindLicenseKey(): string
    {
        if ($this->hasEnv('MAXMIND_LICENSE_KEY') === true) {
            return $this->getEnv('MAXMIND_LICENSE_KEY');
        }

        return '';
    }

    public function getMaxMindDownloadUrl(): string
    {
        return $this->maxMindDownloadUrl;
    }

    public function getGeoIpDatabaseFolder(): string
    {
        return $this->getPath($this->geoIpDatabaseFolder);
    }

    public function getAsnDatabasePath(): string
    {
        if ($this->hasEnv('MAXMIND_LICENSE_KEY') === true) {
            return $this->getPath($this->defaultGeoLite2Paths['GeoLite2-ASN']);
        }

        return $this->getEnv('ASN_DATABASE');
    }

    public function getCountryDatabasePath(): string
    {
        if ($this->hasEnv('MAXMIND_LICENSE_KEY') === true) {
            return $this->getPath($this->defaultGeoLite2Paths['GeoLite2-Country']);
        }

        return $this->getEnv('COUNTRY_DATABASE');
    }

    public function getTimezone(): string
    {
        return $this->getEnv('TIMEZONE');
    }

    public function getSystemLogTimezone(): string
    {
        return $this->getEnv('SYSTEM_LOG_TIMEZONE');
    }

    /**
     * Check config from `data.php`
     */
    public function check(): void
    {
        if (file_exists($this->getPath('config.php')) === true) {
            require $this->getPath('config.php');
        }

        $this->checkDashboard();
        $this->checkTimeZones();
    }

    /**
     * Check config for command-line
     *
     * @throws ConfigException if script not run via the command-line.
     * @throws ConfigException if environment variable `IA_LOG_FOLDER` or `IA_LOG_PATHS` is not set.
     */
    public function checkCli(): void
    {
        if (php_sapi_name() !== 'cli') {
            throw new ConfigException('Intruder Alert script must be run via the command-line.');
        }

        if ($this->hasEnv('LOG_PATHS') === false && $this->hasEnv('LOG_FOLDER') === false) {
            throw new ConfigException('Environment variable IA_LOG_FOLDER or IA_LOG_PATHS must be set');
        }

        $this->checkLogPaths();
        $this->checkLogFolder();
        $this->checkDataFolder();
        $this->checkMaxMindLicenseKey();
        $this->checkDatabases();
    }

    /**
     * Check data folder
     *
     * @throws ConfigException if data folder could not be created.
     */
    private function checkDataFolder(): void
    {
        $folderPath = $this->getPath('data');

        if (file_exists($folderPath) === false) {
            if (mkdir($folderPath, 0660) === false) {
                throw new ConfigException('Failed to create data folder');
            }
        }
    }

    /**
     * Check log paths
     *
     * @throws ConfigException if `IA_LOG_PATHS` environment variable is empty.
     * @throws ConfigException if Fail2ban log folder does not exist.
     * @throws ConfigException if Fail2ban log folder not readable.
     */
    private function checkLogPaths(): void
    {
        if ($this->hasEnv('LOG_PATHS') === true && $this->getEnv('LOG_PATHS') === '') {
            throw new ConfigException('fail2ban log paths variable can not be empty [IA_LOG_PATHS]');
        }
    }

    /**
     * Check log folder
     *
     * @throws ConfigException if `IA_LOG_FOLDER` environment variable not set.
     * @throws ConfigException if Fail2ban log folder does not exist.
     * @throws ConfigException if Fail2ban log folder not readable.
     */
    private function checkLogFolder(): void
    {
        if ($this->hasEnv('LOG_PATHS') === false) {
            if ($this->hasEnv('LOG_FOLDER') === false || $this->getEnv('LOG_FOLDER') === '') {
                throw new ConfigException('fail2ban log folder variable can not be empty [IA_LOG_FOLDER]');
            }

            if (file_exists($this->getEnv('LOG_FOLDER')) === false) {
                throw new ConfigException('fail2ban log folder does not exist [IA_LOG_FOLDER]');
            }

            if (is_readable($this->getEnv('LOG_FOLDER')) === false) {
                throw new ConfigException('fail2ban log folder is not readable [IA_LOG_FOLDER]');
            }
        }
    }

    /**
     * Check MaxMind license key
     *
     * @throws ConfigException if `IA_MAXMIND_LICENSE_KEY` environment variable is empty.
     */
    private function checkMaxMindLicenseKey(): void
    {
        if ($this->hasEnv('MAXMIND_LICENSE_KEY') === true && $this->getEnv('MAXMIND_LICENSE_KEY') === '') {
            throw new ConfigException('MaxMind license key can not be empty [IA_MAXMIND_LICENSE_KEY]');
        }
    }

    /**
     * Check GeoLite2 databases
     *
     * @throws ConfigException if `IA_ASN_DATABASE` environment variable not set.
     * @throws ConfigException if `IA_COUNTRY_DATABASE` environment variable not set.
     * @throws ConfigException if GeoLite2 ASN database does not exist.
     * @throws ConfigException if GeoLite2 Country database does not exist.
     * @throws ConfigException if GeoLite2 ASN database not readable.
     * @throws ConfigException if GeoLite2 Country database not readable.
     * @throws ConfigException if GeoLite2 database is invalid.
     */
    private function checkDatabases(): void
    {
        if ($this->hasEnv('MAXMIND_LICENSE_KEY') === false) {
            if ($this->hasEnv('ASN_DATABASE') === false || $this->getEnv('ASN_DATABASE') === '') {
                throw new ConfigException('GeoLite2 ASN database path must be set [IA_ASN_DATABASE]');
            }

            if ($this->hasEnv('COUNTRY_DATABASE') === false || $this->getEnv('COUNTRY_DATABASE') === '') {
                throw new ConfigException('GeoLite2 Country database path must be set [IA_COUNTRY_DATABASE]');
            }

            if (file_exists($this->getEnv('ASN_DATABASE')) === false) {
                throw new ConfigException('GeoLite2 ASN database not found [IA_ASN_DATABASE]');
            }

            if (file_exists($this->getEnv('COUNTRY_DATABASE')) === false) {
                throw new ConfigException('GeoLite2 Country database not found [IA_COUNTRY_DATABASE]');
            }

            /*if (is_readable($this->getEnv('ASN_DATABASE')) === false) {
                throw new ConfigException('GeoLite2 ASN database is not readable [IA_ASN_DATABASE]');
            }

            if (is_readable($this->getEnv('COUNTRY_DATABASE')) === false) {
                throw new ConfigException('GeoLite2 Country database is not readable [IA_COUNTRY_DATABASE]');
            }*/

            $this->checkDatabaseIsValid($this->getEnv('ASN_DATABASE'));
            $this->checkDatabaseIsValid($this->getEnv('COUNTRY_DATABASE'));
        }
    }

    /**
     * Check GeoLite2 database is valid
     *
     * @param string $path Path of GeoLite2 database
     *
     * @throws ConfigException if GeoLite2 database is invalid.
     */
    private function checkDatabaseIsValid(string $path): void
    {
        try {
            new Reader($path);
        } catch (InvalidDatabaseException) {
            throw new ConfigException('GeoLite2 database is invalid: ' . $path);
        }
    }

    /**
     * Check timezones
     *
     * @throws ConfigException if `TIMEZONE` environment variable is not set or empty.
     * @throws ConfigException if `SYSTEM_LOG_TIMEZONE` environment variable is empty.
     * @throws ConfigException if an unknown timezone given in either `TIMEZONE` or `SYSTEM_LOG_TIMEZONE`.
     */
    private function checkTimeZones(): void
    {
        if ($this->hasEnv('TIMEZONE') === false || $this->getEnv('TIMEZONE') === '') {
            throw new ConfigException('Timezone environment variable must be set [IA_TIMEZONE]');
        }

        if (in_array($this->getEnv('TIMEZONE'), \DateTimeZone::listIdentifiers(\DateTimeZone::ALL)) === false) {
            throw new ConfigException('Unknown timezone given [IA_TIMEZONE]');
        }

        if ($this->hasEnv('SYSTEM_LOG_TIMEZONE') === true) {
            if ($this->getEnv('SYSTEM_LOG_TIMEZONE') === '') {
                throw new ConfigException('Timezone can not be empty [IA_SYSTEM_LOG_TIMEZONE]');
            }

            $valid = in_array($this->getEnv('SYSTEM_LOG_TIMEZONE'), \DateTimeZone::listIdentifiers(\DateTimeZone::ALL));

            if ($valid === false) {
                throw new ConfigException('Unknown timezone given [IA_SYSTEM_LOG_TIMEZONE]');
            }
        } else {
            $this->setEnv('SYSTEM_LOG_TIMEZONE', date_default_timezone_get());
        }

        date_default_timezone_set($this->getEnv('TIMEZONE'));
    }

    /**
     * Check dashboard variables
     *
     * @throws ConfigException if environment variable `IA_DISABLE_CHARTS` is not a boolean.
     * @throws ConfigException if environment variable `IA_DISABLE_DASH_UPDATES` is not a boolean.
     * @throws ConfigException if environment variable `IA_DASH_DAEMON_LOG` is not a boolean.
     */
    private function checkDashboard(): void
    {
        if ($this->hasEnv('DASH_CHARTS') === true && $this->isEnvBoolean('DASH_CHARTS') === false) {
            throw new ConfigException('Charts environment variable must be true or false [IA_DASH_CHARTS]');
        }

        if ($this->hasEnv('DASH_UPDATES') === true && $this->isEnvBoolean('DASH_UPDATES') === false) {
            throw new ConfigException('Dashboard updates environment variable must be true or false [IA_DASH_UPDATES]');
        }

        if ($this->hasEnv('DASH_DAEMON_LOG') === true && $this->isEnvBoolean('DASH_DAEMON_LOG') === false) {
            throw new ConfigException(
                'Dashboard daemon log environment variable must be true or false [DASH_DAEMON_LOG]'
            );
        }
    }

    /**
     * Check if a environment variable is a boolean
     *
     * @param string $name Variable name excluding prefix
     */
    private function isEnvBoolean(string $name): bool
    {
        return in_array($this->getEnv($name), ['true', 'false']);
    }

    /**
     * Check for an environment variable
     *
     * @param string $name Variable name excluding prefix
     */
    private function hasEnv(string $name): bool
    {
        if (getenv($this->envPrefix . $name) === false) {
            return false;
        }

        return true;
    }

    /**
     * Get an environment variable
     *
     * @param string $name Variable name excluding prefix
     */
    private function getEnv(string $name): mixed
    {
        return getenv($this->envPrefix . $name);
    }

    /**
     * Set an environment variable
     *
     * @param string $name Variable name excluding prefix
     * @param string $value Variable value
     */
    private function setEnv(string $name, string $value): void
    {
        putenv(sprintf('%s%s=%s', $this->envPrefix, $name, $value));
    }
}

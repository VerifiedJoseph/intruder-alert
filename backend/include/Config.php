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

    /** @var string $dataFilepath Report data filepath */
    private string $dataFilepath = 'data/data.json';

    /** @var string $cacheFilepath Cache filepath */
    private string $cacheFilepath = 'data/cache.json';

    /** @var array<string, mixed> $config Loaded config */
    private array $config = [
        'log_paths' => '',
        'log_folder' => '',
        'maxmind_license_key' => '',
        'asn_database_path' => '',
        'country_database_path' => '',
        'timezone' => '',
        'log_timezone' => '',
        'dash_charts' => true,
        'dash_updates' => true,
        'dash_daemon_log' => true
    ];

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
        return Version::get();
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
        return $this->config['dash_charts'];
    }

    public function getDashUpdatesStatus(): bool
    {
        return $this->config['dash_updates'];
    }

    public function getDashDaemonLogStatus(): bool
    {
        return $this->config['dash_daemon_log'];
    }

    public function getLogFolder(): string
    {
        return $this->config['log_folder'];
    }

    public function getLogPaths(): string
    {
        return $this->config['log_paths'];
    }

    public function getMaxMindLicenseKey(): string
    {
        return $this->config['maxmind_license_key'];
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
        if ($this->config['maxmind_license_key'] !== '') {
            return $this->getPath($this->defaultGeoLite2Paths['GeoLite2-ASN']);
        }

        return $this->config['asn_database_path'];
    }

    public function getCountryDatabasePath(): string
    {
        if ($this->config['maxmind_license_key'] !== '') {
            return $this->getPath($this->defaultGeoLite2Paths['GeoLite2-Country']);
        }

        return $this->config['country_database_path'];
    }

    public function getTimezone(): string
    {
        return $this->config['timezone'];
    }

    public function getSystemLogTimezone(): string
    {
        return $this->config['log_timezone'];
    }

    public function getCacheFilePath(): string
    {
        return $this->getPath($this->cacheFilepath);
    }

    public function getDataFilePath(): string
    {
        return $this->getPath($this->dataFilepath);
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
        $this->checkFolder($this->getPath('data'));
        $this->checkFolder($this->getGeoIpDatabaseFolder());
        $this->checkMaxMindLicenseKey();
        $this->checkDatabases();
    }

    /**
     * Check for folder and create when needed
     *
     * @param string $path Folder path
     * @throws ConfigException if data folder could not be created.
     */
    public function checkFolder(string $path): void
    {
        if (file_exists($path) === false) {
            if (mkdir($path, 0660) === false) {
                throw new ConfigException('Failed to create folder: ' . $path);
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
    public function checkLogPaths(): void
    {
        if ($this->hasEnv('LOG_PATHS') === true && $this->getEnv('LOG_PATHS') === '') {
            throw new ConfigException('fail2ban log paths variable can not be empty [IA_LOG_PATHS]');
        }

        $this->config['log_paths'] = $this->getEnv('LOG_PATHS');
    }

    /**
     * Check log folder
     *
     * @throws ConfigException if `IA_LOG_FOLDER` environment variable not set.
     * @throws ConfigException if Fail2ban log folder does not exist or not readable.
     */
    public function checkLogFolder(): void
    {
        if ($this->hasEnv('LOG_PATHS') === false) {
            if ($this->hasEnv('LOG_FOLDER') === false || $this->getEnv('LOG_FOLDER') === '') {
                throw new ConfigException('fail2ban log folder variable can not be empty [IA_LOG_FOLDER]');
            }

            $folder = $this->getEnv('LOG_FOLDER');

            if (file_exists($folder) === false || is_readable($folder) === false) {
                throw new ConfigException('fail2ban log folder does not exist or not readable [IA_LOG_FOLDER]');
            }

            $this->config['log_folder'] = $folder;
        }
    }

    /**
     * Check MaxMind license key
     *
     * @throws ConfigException if `IA_MAXMIND_LICENSE_KEY` environment variable is empty.
     */
    public function checkMaxMindLicenseKey(): void
    {
        if ($this->hasEnv('MAXMIND_LICENSE_KEY') === true && $this->getEnv('MAXMIND_LICENSE_KEY') === '') {
            throw new ConfigException('MaxMind license key can not be empty [IA_MAXMIND_LICENSE_KEY]');
        }

        $this->config['maxmind_license_key'] = $this->getEnv('MAXMIND_LICENSE_KEY');
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
    public function checkDatabases(): void
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

            $this->checkDatabaseIsValid($this->getEnv('ASN_DATABASE'));
            $this->checkDatabaseIsValid($this->getEnv('COUNTRY_DATABASE'));

            $this->config['asn_database_path'] = $this->getEnv('ASN_DATABASE');
            $this->config['country_database_path'] = $this->getEnv('COUNTRY_DATABASE');
        }
    }

    /**
     * Check GeoLite2 database is valid
     *
     * @param string $path Path of GeoLite2 database
     *
     * @throws ConfigException if GeoLite2 database is invalid.
     */
    public function checkDatabaseIsValid(string $path): void
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
    public function checkTimeZones(): void
    {
        if ($this->hasEnv('TIMEZONE') === false || $this->getEnv('TIMEZONE') === '') {
            throw new ConfigException('Timezone environment variable must be set [IA_TIMEZONE]');
        }

        if (in_array($this->getEnv('TIMEZONE'), \DateTimeZone::listIdentifiers(\DateTimeZone::ALL)) === false) {
            throw new ConfigException('Unknown timezone given [IA_TIMEZONE]');
        }

        $this->config['timezone'] = $this->getEnv('TIMEZONE');

        if ($this->hasEnv('SYSTEM_LOG_TIMEZONE') === true) {
            if ($this->getEnv('SYSTEM_LOG_TIMEZONE') === '') {
                throw new ConfigException('Timezone can not be empty [IA_SYSTEM_LOG_TIMEZONE]');
            }

            $valid = in_array($this->getEnv('SYSTEM_LOG_TIMEZONE'), \DateTimeZone::listIdentifiers(\DateTimeZone::ALL));

            if ($valid === false) {
                throw new ConfigException('Unknown timezone given [IA_SYSTEM_LOG_TIMEZONE]');
            }

            $this->config['log_timezone'] = $this->getEnv('SYSTEM_LOG_TIMEZONE');
        } else {
            $this->config['log_timezone'] = date_default_timezone_get();
        }

        date_default_timezone_set($this->config['timezone']);
    }

    /**
     * Check dashboard variables
     *
     * @throws ConfigException if environment variable `IA_DISABLE_CHARTS` is not a boolean.
     * @throws ConfigException if environment variable `IA_DISABLE_DASH_UPDATES` is not a boolean.
     * @throws ConfigException if environment variable `IA_DASH_DAEMON_LOG` is not a boolean.
     */
    public function checkDashboard(): void
    {
        if ($this->hasEnv('DASH_CHARTS') === true) {
            if ($this->isEnvBoolean('DASH_CHARTS') === false) {
                throw new ConfigException('Charts environment variable must be true or false [IA_DASH_CHARTS]');
            }

            $this->config['dash_charts'] = $this->getEnv('DASH_CHARTS');
        }

        if ($this->hasEnv('DASH_UPDATES') === true) {
            if ($this->isEnvBoolean('DASH_UPDATES') === false) {
                throw new ConfigException(
                    'Dashboard updates environment variable must be true or false [IA_DASH_UPDATES]'
                );
            }

            $this->config['dash_updates'] = $this->getEnv('DASH_UPDATES');
        }

        if ($this->hasEnv('DASH_DAEMON_LOG') === true) {
            if ($this->isEnvBoolean('DASH_DAEMON_LOG') === false) {
                throw new ConfigException(
                    'Dashboard daemon log environment variable must be true or false [DASH_DAEMON_LOG]'
                );
            }

            $this->config['dash_daemon_log'] = $this->getEnv('DASH_DAEMON_LOG');
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
}

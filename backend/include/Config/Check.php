<?php

namespace IntruderAlert\Config;

use IntruderAlert\Exception\ConfigException;
use GeoIp2\Database\Reader;
use MaxMind\Db\Reader\InvalidDatabaseException;

class Check extends Base
{
    /** @var array<string, mixed> $config Config */
    private array $config = [];

    /**
     * @param array<string, mixed> $defaults Config defaults
     */
    public function __construct(array $defaults)
    {
        $this->config = $defaults;
    }

    /**
     * Returns config
     *
     * @return array<string, mixed>
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Check php version
     *
     * @param string $version php version
     * @param string $minimumVersion Minimum required PHP version
     * @throws ConfigException if PHP version not supported.
     */
    public function version(string $version, string $minimumVersion): void
    {
        if (version_compare($version, $minimumVersion) === -1) {
            throw new ConfigException('Intruder Alert requires at least PHP version ' . $minimumVersion);
        }
    }

    /**
     * Check for required php extensions
     *
     * @param array<int, string> $required Required php extensions
     * @throws ConfigException if a required PHP extension is not loaded.
     */
    public function extensions(array $required): void
    {
        foreach ($required as $ext) {
            if (extension_loaded($ext) === false) {
                throw new ConfigException(sprintf('PHP extension error: %s extension not loaded.', $ext));
            }
        }
    }

    /**
     * Check for folder and create when needed
     *
     * @param string $path Folder path
     * @throws ConfigException if data folder could not be created.
     */
    public function folder(string $path): void
    {
        if (file_exists($path) === false) {
            if (mkdir($path) === false) {
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
    public function logPaths(): void
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
    public function logFolder(): void
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
    public function maxMindLicenseKey(): void
    {
        if ($this->hasEnv('MAXMIND_LICENSE_KEY') === true) {
            if ($this->getEnv('MAXMIND_LICENSE_KEY') === '') {
                throw new ConfigException('MaxMind license key can not be empty [IA_MAXMIND_LICENSE_KEY]');
            }

            $this->config['maxmind_license_key'] = $this->getEnv('MAXMIND_LICENSE_KEY');
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
    public function databases(): void
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

            $this->isDatabaseValid($this->getEnv('ASN_DATABASE'));
            $this->isDatabaseValid($this->getEnv('COUNTRY_DATABASE'));

            $this->config['asn_database_path'] = $this->getEnv('ASN_DATABASE');
            $this->config['country_database_path'] = $this->getEnv('COUNTRY_DATABASE');
        }
    }

    /**
     * Check dashboard timezone
     *
     * @throws ConfigException if `IA_TIMEZONE` environment variable is not set or empty.
     * @throws ConfigException if an unknown timezone given in `IA_TIMEZONE`.
     */
    public function timezone(): void
    {
        if ($this->hasEnv('TIMEZONE') === false || $this->getEnv('TIMEZONE') === '') {
            throw new ConfigException('Timezone environment variable must be set [IA_TIMEZONE]');
        }

        if (in_array($this->getEnv('TIMEZONE'), \DateTimeZone::listIdentifiers(\DateTimeZone::ALL)) === false) {
            throw new ConfigException('Unknown timezone given [IA_TIMEZONE]');
        }

        $this->config['timezone'] = $this->getEnv('TIMEZONE');
    }

    /**
     * Check system log timezone (`IA_SYSTEM_LOG_TIMEZONE`)
     *
     * @throws ConfigException if `IA_SYSTEM_LOG_TIMEZONE` environment variable is empty.
     * @throws ConfigException if an unknown timezone given in `IA_SYSTEM_LOG_TIMEZONE`.
     */
    public function systemLogTimezone(): void
    {
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
    }

    /**
     * Check dashboard variables
     *
     * @throws ConfigException if environment variable `IA_DISABLE_CHARTS` is not a boolean.
     * @throws ConfigException if environment variable `IA_DISABLE_DASH_UPDATES` is not a boolean.
     * @throws ConfigException if environment variable `IA_DASH_DAEMON_LOG` is not a boolean.
     */
    public function dashboard(): void
    {
        if ($this->hasEnv('DASH_CHARTS') === true) {
            if ($this->isEnvBoolean('DASH_CHARTS') === false) {
                throw new ConfigException('Charts environment variable must be true or false [IA_DASH_CHARTS]');
            }

            $this->config['dash_charts'] = filter_var($this->getEnv('DASH_CHARTS'), FILTER_VALIDATE_BOOLEAN);
        }

        if ($this->hasEnv('DASH_UPDATES') === true) {
            if ($this->isEnvBoolean('DASH_UPDATES') === false) {
                throw new ConfigException(
                    'Dashboard updates environment variable must be true or false [IA_DASH_UPDATES]'
                );
            }

            $this->config['dash_updates'] = filter_var($this->getEnv('DASH_UPDATES'), FILTER_VALIDATE_BOOLEAN);
        }

        if ($this->hasEnv('DASH_DAEMON_LOG') === true) {
            if ($this->isEnvBoolean('DASH_DAEMON_LOG') === false) {
                throw new ConfigException(
                    'Dashboard daemon log environment variable must be true or false [DASH_DAEMON_LOG]'
                );
            }

            $this->config['dash_daemon_log'] = filter_var($this->getEnv('DASH_DAEMON_LOG'), FILTER_VALIDATE_BOOLEAN);
        }
    }

    /**
     * Check GeoLite2 database is valid
     *
     * @param string $path Path of GeoLite2 database
     *
     * @throws ConfigException if GeoLite2 database is invalid.
     */
    public function isDatabaseValid(string $path): void
    {
        try {
            new Reader($path);
        } catch (InvalidDatabaseException) {
            throw new ConfigException('GeoLite2 database is invalid: ' . $path);
        }
    }
}

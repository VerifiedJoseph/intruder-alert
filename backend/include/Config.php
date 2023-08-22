<?php

use Exception\ConfigException;

class Config
{
	/** @var string $minPhpVersion Minimum PHP version */
	private static string $minPhpVersion = '8.1.0';

    private static string $path;

    private static string $envPrefix = 'IA_';

    /**
     * Set backend directory
     * 
     * @param string $path
     */
    public static function setDir(string $path): void
    {
        self::$path = $path . DIRECTORY_SEPARATOR;
    }

    /**
     * Get absolute path of a file
     * 
     * @param string $file
     */
    public static function getPath(string $file = ''): string
    {
        return self::$path . $file;
    }

    public static function getLogFolder(): string
    {
        return self::getEnv('LOG_FOLDER');
    }

    public static function getLogPaths(): string
    {
        if (self::hasEnv('LOG_PATHS') === true) {
            return self::getEnv('LOG_PATHS');
        }

        return '';
    }

    public static function getAsnDatabasePath(): string
    {
        return self::getEnv('ASN_DATABASE');
    }

    public static function getCountryDatabasePath(): string
    {
        return self::getEnv('COUNTRY_DATABASE');
    }

    public static function getTimezone(): string
    {
        return self::getEnv('TIMEZONE');
    }

    public static function getSystemLogTimezone(): string
    {
        return self::getEnv('SYSTEM_LOG_TIMEZONE');
    }

    /**
     * Check config
     * 
     * @throws ConfigException if script not run via the command-line.
     * @throws ConfigException if PHP version not supported.
     * @throws ConfigException if environment variable `IA_LOG_FOLDER` or `IA_LOG_PATHS` is not set.
     */
    public static function check(): void
    {
        if (php_sapi_name() !== 'cli') {
            throw new ConfigException('Intruder Alert script must be run via the command-line.');
        }

		if(version_compare(PHP_VERSION, self::$minPhpVersion) === -1) {
			throw new ConfigException('Intruder Alert requires at least PHP version ' . self::$minPhpVersion);
		}

        if (file_exists(self::getPath('config.php')) === true) {
            require self::getPath('config.php');
        }

        if (self::hasEnv('LOG_PATHS') === false && self::hasEnv('LOG_FOLDER') === false) {
            throw new ConfigException('Environment variable IA_LOG_FOLDER or IA_LOG_PATHS must be set');
        }

        self::checkLogPaths();
        self::checkLogFolder();
        self::checkDatabases();
        self::checkTimeZones();
    }

    /**
     * Check log paths
     * 
     * @throws ConfigException if `IA_LOG_PATHS` environment variable is empty.
     * @throws ConfigException if Fail2ban log folder does not exist.
     * @throws ConfigException if Fail2ban log folder not readable.
     */
    private static function checkLogPaths(): void
    {
        if (self::hasEnv('LOG_PATHS') === true) {
            if (self::getEnv('LOG_PATHS') === '') {
                throw new ConfigException('fail2ban logs environment variable can not be empty [LOG_PATHS]');
            }
        }
    }

    /**
     * Check log folder
     * 
     * @throws ConfigException if `IA_LOG_FOLDER` environment variable not set.
     * @throws ConfigException if Fail2ban log folder does not exist.
     * @throws ConfigException if Fail2ban log folder not readable.
     */
    private static function checkLogFolder(): void
    {
        if (self::hasEnv('LOG_PATHS') === false) {
            if (self::hasEnv('LOG_FOLDER') === false || self::getEnv('LOG_FOLDER') === '') {
                throw new ConfigException('fail2ban log folder must be set [LOG_FOLDER]');
            }
    
            if (file_exists(self::getEnv('LOG_FOLDER')) === false) {
                throw new ConfigException('fail2ban log folder does not exist [LOG_FOLDER]');
            }
    
            if (is_readable(self::getEnv('LOG_FOLDER')) === false) {
                throw new ConfigException('fail2ban log folder is not readable [LOG_FOLDER]');
            }
        }
    }

    /**
     * Check GeoLite2 databases
     * 
     * @throws ConfigException if `ASN_DATABASE` environment variable not set.
     * @throws ConfigException if `COUNTRY_DATABASE` environment variable not set.
     * @throws ConfigException if GeoLite2 ASN database does not exist.
     * @throws ConfigException if GeoLite2 Country database does not exist.
     * @throws ConfigException if GeoLite2 ASN database not readable.
     * @throws ConfigException if GeoLite2 Country database not readable.
     * @throws ConfigException if GeoLite2 database is invalid.
     */
    private static function checkDatabases(): void
    {
        if (self::hasEnv('ASN_DATABASE') === false || self::getEnv('ASN_DATABASE') === '') {
            throw new ConfigException('GeoLite2 ASN database path must be set [ASN_DATABASE]');
        }

        if (self::hasEnv('COUNTRY_DATABASE') === false || self::getEnv('COUNTRY_DATABASE') === '') {
            throw new ConfigException('GeoLite2 Country database path must be set [COUNTRY_DATABASE]');
        }

        if (file_exists(self::getEnv('ASN_DATABASE')) === false) {
            throw new ConfigException('GeoLite2 ASN database not found [ASN_DATABASE]');
        }

        if (file_exists(self::getEnv('COUNTRY_DATABASE')) === false) {
            throw new ConfigException('GeoLite2 Country database not found [COUNTRY_DATABASE]');
        }

        if (is_readable(self::getEnv('ASN_DATABASE')) === false) {
            throw new ConfigException('GeoLite2 ASN database is not readable [ASN_DATABASE]');
        }
    
        if (is_readable(self::getEnv('COUNTRY_DATABASE')) === false) {
            throw new ConfigException('GeoLite2 Country database is not readable [COUNTRY_DATABASE]');
        }

        self::checkDatabaseIsValid(self::getEnv('ASN_DATABASE'));
        self::checkDatabaseIsValid(self::getEnv('COUNTRY_DATABASE'));
    }

    /**
     * Check GeoLite2 database is valid
     * 
     * @param string $path Path of GeoLite2 database
     *
     * @throws ConfigException if GeoLite2 database is invalid.
     */
    static private function checkDatabaseIsValid(string $path): void
    {
        try {
            new GeoIp2\Database\Reader($path);
        } catch (MaxMind\Db\Reader\InvalidDatabaseException) {
            throw new ConfigException('GeoLite2 database is invalid: ' . $path);
        }
    }

    /**
     * Check timezones
     * 
     * @throws ConfigException if `TIMEZONE` environment variable is empty.
     * @throws ConfigException if `SYSTEM_LOG_TIMEZONE` environment variable is empty.
     * @throws ConfigException if an unknown timezone given in either `TIMEZONE` or `SYSTEM_LOG_TIMEZONE`.
     */
    static private function checkTimeZones(): void
    {
        if (self::hasEnv('TIMEZONE') === true) {
            if (self::getEnv('TIMEZONE') === '') {
                throw new ConfigException('Time zone can not be empty [TIMEZONE]');
            }

            if (in_array(self::getEnv('TIMEZONE'), DateTimeZone::listIdentifiers(DateTimeZone::ALL)) === false) {
                throw new ConfigException('Unknown time zone given [TIMEZONE]');
            }

            date_default_timezone_set(self::getEnv('TIMEZONE'));
        }

        if (self::hasEnv('SYSTEM_LOG_TIMEZONE') === true ) {
            if (self::getEnv('SYSTEM_LOG_TIMEZONE') === '') {
                throw new ConfigException('Time zone can not be empty [SYSTEM_LOG_TIMEZONE]');
            }

            if (in_array(self::getEnv('SYSTEM_LOG_TIMEZONE'), DateTimeZone::listIdentifiers(DateTimeZone::ALL)) === false) {
                throw new ConfigException('Unknown time zone given [SYSTEM_LOG_TIMEZONE]');
            }
        } else {
            self::setEnv('SYSTEM_LOG_TIMEZONE', 'UTC');
        }
    }

    /**
     * Check for an environment variable
     * 
     * @param string $name Variable name excluding prefix
     */
    static private function hasEnv(string $name): bool
    {
        if (getenv(self::$envPrefix . $name) === false) {
            return false;
        }

       return true;
    }

    /**
     * Get an environment variable
     * 
     * @param string $name Variable name excluding prefix
     */
    static private function getEnv(string $name): mixed
    {
       return getenv(self::$envPrefix . $name);
    }

    /**
     * Set an environment variable
     * 
     * @param string $name Variable name excluding prefix
     * @param string $value Variable value
     */
    static private function setEnv(string $name, string $value): void
    {
        putenv(sprintf('%s%s=%s', self::$envPrefix, $name, $value));
    }
}

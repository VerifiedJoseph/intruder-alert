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
     * @vsr string $path
     */
    public static function setDir(string $path): void
    {
        self::$path = $path . DIRECTORY_SEPARATOR;
    }

    /**
     * Get absolute path of a file
     * 
     * @vsr string $file
     */
    public static function getPath(string $file = ''): string
    {
        return self::$path . $file;
    }

    public static function getLogFolder(): string
    {
        return self::getEnv('LOG_FOLDER');
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

        self::checkLogFolder();
        self::checkDatabases();
        self::checkTimeZones();
    }

    private static function checkLogFolder(): void
    {
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

    static private function checkDatabaseIsValid(string $path): void
    {
        try {
            new GeoIp2\Database\Reader($path);
        } catch (MaxMind\Db\Reader\InvalidDatabaseException) {
            throw new ConfigException('GeoLite2 database is invalid: ' . $path);
        }
    }

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
     * @var string $name Variable name excluding prefix
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
     * @var string $name Variable name excluding prefix
     */
    static private function getEnv(string $name)
    {
       return getenv(self::$envPrefix . $name);
    }

    /**
     * Set an environment variable
     * 
     * @var string $name Variable name excluding prefix
     * @var string $value Variable value
     */
    static private function setEnv(string $name, string $value): void
    {
        putenv(sprintf('%s%s=%s', self::$envPrefix, $name, $value));
    }
}

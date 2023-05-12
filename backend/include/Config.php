<?php

use Exception\ConfigException;

class Config
{
    /**
     * Check config
     */
    public static function check(): void
    {
        if (php_sapi_name() !== 'cli') {
            throw new ConfigException('Intruder Alert script must be run via the command-line.');
        }

        if (file_exists('config.php') === false) {
            throw new ConfigException('config file not found (../config.php)');
        }

        require 'config.php';

        self::checkLogFolder();
        self::checkDatabases();
        self::checkTimeZones();
    }

    private static function checkLogFolder(): void
    {
        if (defined('LOG_FOLDER') === false || constant('LOG_FOLDER') === '') {
            throw new ConfigException('fail2ban log folder must be set [LOG_FOLDER]');
        }

        if (file_exists(constant('LOG_FOLDER')) === false) {
            throw new ConfigException('fail2ban log folder does not exist [LOG_FOLDER]');
        }

        if (is_readable(constant('LOG_FOLDER')) === false) {
            throw new ConfigException('fail2ban log folder is not readable [LOG_FOLDER]');
        }
    }

    private static function checkDatabases(): void
    {
        if (defined('ASN_DATABASE') === false || constant('ASN_DATABASE') === '') {
            throw new ConfigException('GeoLite2 ASN database path must be set [ASN_DATABASE]');
        }

        if (defined('COUNTRY_DATABASE') === false || constant('COUNTRY_DATABASE') === '') {
            throw new ConfigException('GeoLite2 Country database path must be set [COUNTRY_DATABASE]');
        }

        if (file_exists(constant('ASN_DATABASE')) === false) {
            throw new ConfigException('GeoLite2 ASN database not found [ASN_DATABASE]');
        }

        if (file_exists(constant('COUNTRY_DATABASE')) === false) {
            throw new ConfigException('GeoLite2 Country database not found [COUNTRY_DATABASE]');
        }

        if (is_readable(constant('ASN_DATABASE')) === false) {
            throw new ConfigException('GeoLite2 ASN database is not readable [ASN_DATABASE]');
        }
    
        if (is_readable(constant('COUNTRY_DATABASE')) === false) {
            throw new ConfigException('GeoLite2 Country database is not readable [COUNTRY_DATABASE]');
        }

        self::checkDatabaseIsValid(constant('ASN_DATABASE'));
        self::checkDatabaseIsValid(constant('COUNTRY_DATABASE'));
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
        if (defined('TIMEZONE') === true) {
            if (constant('TIMEZONE') === '') {
                throw new ConfigException('Time zone can not be empty [TIMEZONE]');
            }

            if (in_array(constant('TIMEZONE'), DateTimeZone::listIdentifiers(DateTimeZone::ALL)) === false) {
                throw new ConfigException('Unknown time zone given [TIMEZONE]');
            }

            date_default_timezone_set(constant('TIMEZONE'));
        }

        if (defined('SYSTEM_LOG_TIMEZONE') === true ) {
            if (constant('SYSTEM_LOG_TIMEZONE') === '') {
                throw new ConfigException('Time zone can not be empty [SYSTEM_LOG_TIMEZONE]');
            }

            if (in_array(constant('SYSTEM_LOG_TIMEZONE'), DateTimeZone::listIdentifiers(DateTimeZone::ALL)) === false) {
                throw new ConfigException('Unknown time zone given [SYSTEM_LOG_TIMEZONE]');
            }
        } else {
            define('SYSTEM_LOG_TIMEZONE', 'UTC');
        }
    }
}

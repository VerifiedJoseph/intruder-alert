<?php

use Exception\ConfigException;

class Config
{
    /**
     * Check config
     */
    public static function check(): void
    {
        if (file_exists('config.php') === false) {
            throw new ConfigException('config file not found (../config.php)');
        }

        require 'config.php';

        self::checkLogFolder();
        self::checkDatabases();
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
    }
}

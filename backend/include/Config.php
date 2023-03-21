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
        if (defined('GEO_IP_ASN') === false || constant('GEO_IP_ASN') === '') {
            throw new ConfigException('GeoLite2 ASN database path must be set [GEO_IP_ASN]');
        }

        if (defined('GEO_IP_COUNTRY') === false || constant('GEO_IP_COUNTRY') === '') {
            throw new ConfigException('GeoLite2 Country database path must be set [GEO_IP_COUNTRY]');
        }

        if (file_exists(constant('GEO_IP_ASN')) === false) {
            throw new ConfigException('GeoLite2 ASN database not found [GEO_IP_ASN]');
        }

        if (file_exists(constant('GEO_IP_COUNTRY')) === false) {
            throw new ConfigException('GeoLite2 Country database not found [GEO_IP_COUNTRY]');
        }

        if (is_readable(constant('GEO_IP_ASN')) === false) {
            throw new ConfigException('GeoLite2 ASN database is not readable [GEO_IP_ASN]');
        }
    
        if (is_readable(constant('GEO_IP_COUNTRY')) === false) {
            throw new ConfigException('GeoLite2 Country database is not readable [GEO_IP_COUNTRY]');
        }
    }
}

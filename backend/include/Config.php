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
}

<?php

use Exception\ConfigException;

class Config
{
	/** @var string $minPhpVersion Minimum PHP version */
	private string $minPhpVersion = '8.1.0';

    private string $path;

    private string $envPrefix = 'IA_';

    /** @var array<string, string> $defaultGeoLite2Paths Default GeoLite2 database paths */
    private array $defaultGeoLite2Paths = [
        'GeoLite2-ASN' => 'data/geoip2/GeoLite2-ASN/GeoLite2-ASN.mmdb',
        'GeoLite2-Country' => 'data/geoip2/GeoLite2-Country/GeoLite2-Country.mmdb'
    ];

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
     * Check config
     * 
     * @throws ConfigException if script not run via the command-line.
     * @throws ConfigException if PHP version not supported.
     * @throws ConfigException if environment variable `IA_LOG_FOLDER` or `IA_LOG_PATHS` is not set.
     */
    public function check(): void
    {
        if (php_sapi_name() !== 'cli') {
            throw new ConfigException('Intruder Alert script must be run via the command-line.');
        }

		if(version_compare(PHP_VERSION, $this->minPhpVersion) === -1) {
			throw new ConfigException('Intruder Alert requires at least PHP version ' . $this->minPhpVersion);
		}

        if (file_exists($this->getPath('config.php')) === true) {
            require $this->getPath('config.php');
        }

        if ($this->hasEnv('LOG_PATHS') === false && $this->hasEnv('LOG_FOLDER') === false) {
            throw new ConfigException('Environment variable IA_LOG_FOLDER or IA_LOG_PATHS must be set');
        }

        $this->checkLogPaths();
        $this->checkLogFolder();
        $this->checkMaxMindLicenseKey();
        $this->checkDatabases();
        $this->checkTimeZones();
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
        if ($this->hasEnv('LOG_PATHS') === true) {
            if ($this->getEnv('LOG_PATHS') === '') {
                throw new ConfigException('fail2ban logs environment variable can not be empty [IA_LOG_PATHS]');
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
    private function checkLogFolder(): void
    {
        if ($this->hasEnv('LOG_PATHS') === false) {
            if ($this->hasEnv('LOG_FOLDER') === false || $this->getEnv('LOG_FOLDER') === '') {
                throw new ConfigException('fail2ban log folder must be set [IA_LOG_FOLDER]');
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
    private function checkMaxMindLicenseKey():  void
    {
        if ($this->hasEnv('MAXMIND_LICENSE_KEY') === true) {
            if ($this->getEnv('MAXMIND_LICENSE_KEY') === '') {
                throw new ConfigException('MaxMind license key can not be empty [IA_MAXMIND_LICENSE_KEY]');
            }
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
    
            if (is_readable($this->getEnv('ASN_DATABASE')) === false) {
                throw new ConfigException('GeoLite2 ASN database is not readable [IA_ASN_DATABASE]');
            }
        
            if (is_readable($this->getEnv('COUNTRY_DATABASE')) === false) {
                throw new ConfigException('GeoLite2 Country database is not readable [IA_COUNTRY_DATABASE]');
            }
    
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
    private function checkTimeZones(): void
    {
        if ($this->hasEnv('TIMEZONE') === true) {
            if ($this->getEnv('TIMEZONE') === '') {
                throw new ConfigException('Time zone can not be empty [TIMEZONE]');
            }

            if (in_array($this->getEnv('TIMEZONE'), DateTimeZone::listIdentifiers(DateTimeZone::ALL)) === false) {
                throw new ConfigException('Unknown time zone given [IA_TIMEZONE]');
            }

            date_default_timezone_set($this->getEnv('TIMEZONE'));
        }

        if ($this->hasEnv('SYSTEM_LOG_TIMEZONE') === true ) {
            if ($this->getEnv('SYSTEM_LOG_TIMEZONE') === '') {
                throw new ConfigException('Time zone can not be empty [IA_SYSTEM_LOG_TIMEZONE]');
            }

            if (in_array($this->getEnv('SYSTEM_LOG_TIMEZONE'), DateTimeZone::listIdentifiers(DateTimeZone::ALL)) === false) {
                throw new ConfigException('Unknown time zone given [IA_SYSTEM_LOG_TIMEZONE]');
            }
        } else {
            $this->setEnv('SYSTEM_LOG_TIMEZONE', 'UTC');
        }
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

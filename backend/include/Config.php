<?php

namespace IntruderAlert;

use IntruderAlert\Config\Check;
use IntruderAlert\Config\AbstractConfig;
use IntruderAlert\Exception\ConfigException;

class Config extends AbstractConfig
{
    private Check $check;

    /** @var string $minPhpVersion Minimum PHP version */
    private string $minPhpVersion = '8.2.0';

    /** @var array<int, string> $extensions Required PHP extensions */
    private array $extensions = ['curl', 'json', 'phar', 'pcre'];

    private string $path = '';

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

    /** @var array<int, string> $supportedCharts Supported dashboard charts  */
    private array $supportedCharts = [
        'last24hours', 'last48hours', 'last14days', 'last30days'
    ];

    /** @var array<int, int> $supportedPageSizes Supported dashboard table pages sizes  */
    private array $supportedPageSizes = [
        25, 50, 100, 200, 500
    ];

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
        'dash_daemon_log' => true,
        'dash_default_chart' => 'last24hours',
        'dash_page_size' => 25
    ];

    /**
     * @throws ConfigException if PHP version not supported.
     * @throws ConfigException if a required PHP extension is not loaded.
     */
    public function __construct()
    {
        $this->check = new Check($this->config);
        $this->check->version(PHP_VERSION, $this->minPhpVersion);
        $this->check->extensions($this->extensions);
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

    public function getDashDefaultChart(): string
    {
        return $this->config['dash_default_chart'];
    }

    public function getDashPageSize(): int
    {
        return $this->config['dash_page_size'];
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
     * Check config for `data.php`
     */
    public function check(): void
    {
        if (file_exists($this->getPath('config.php')) === true) {
            require $this->getPath('config.php');
        }

        $this->check->timezone();
        $this->check->systemLogTimezone();
        $this->check->dashboardCharts();
        $this->check->dashboardUpdates();
        $this->check->dashboardDaemonLog();
        $this->check->dashboardDefaultChart($this->supportedCharts);
        $this->check->dashboardPageSize($this->supportedPageSizes);

        $this->config = $this->check->getConfig();
    }

    /**
     * Check config for command-line
     *
     * @param string $sapi Interface type
     * @throws ConfigException if script not run via the command-line.
     * @throws ConfigException if environment variable `IA_LOG_FOLDER` or `IA_LOG_PATHS` is not set.
     */
    public function checkCli(string $sapi): void
    {
        if ($sapi !== 'cli') {
            throw new ConfigException('Intruder Alert script must be run via the command-line.');
        }

        if ($this->hasEnv('LOG_PATHS') === false && $this->hasEnv('LOG_FOLDER') === false) {
            throw new ConfigException('Environment variable IA_LOG_FOLDER or IA_LOG_PATHS must be set');
        }

        $this->check->logPaths();
        $this->check->logFolder();
        $this->check->folder($this->getPath('data'));
        $this->check->Folder($this->getGeoIpDatabaseFolder());
        $this->check->maxMindLicenseKey();
        $this->check->databases();
        $this->config = $this->check->getConfig();
    }
}

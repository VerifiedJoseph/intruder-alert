<?php

namespace IntruderAlert\Database;

use IntruderAlert\Config;
use IntruderAlert\Helper\File;
use IntruderAlert\Helper\Output;
use IntruderAlert\Exception\AppException;

class Update
{
    private Config $config;

    /** @var array<int, string> $editions GeoLite2 database editions */
    private array $editions = ['GeoLite2-ASN', 'GeoLite2-Country'];

    private string $destinationDir = 'data/geoip2';

    function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function run(): void
    {
        if ($this->config->getMaxMindLicenseKey() !== '') {
            $path = $this->config->getPath($this->destinationDir);

            if (File::exists($path) === false) {
                mkdir($path);
            }

            if ($this->updateRequired() === true) {
                Output::text('Updating Geoip2 databases', log: true);

                $client = new \tronovav\GeoIP2Update\Client([
                    'license_key' => $this->config->getMaxMindLicenseKey(),
                    'dir' => $path,
                    'editions' => $this->editions,
                ]);

                $client->run();

                foreach ($client->updated() as $message) {
                    Output::text($message, log: true);
                }

                if ($client->errors() != []) {
                    foreach ($client->errors() as $message) {
                        Output::text($message, log: true);
                    }

                    throw new AppException('Failed to update Geoip2 databases');
                }
            }
        }
    }

    /**
     * Check if a database update is required
     */
    private function updateRequired(): bool
    {
        foreach ($this->getDatabasePaths() as $path) {
            if (File::exists($path) === false) {
                return true;
            }

            $fileModTime = (int) filemtime($path);

            if ($this->calculateTimeDiff($fileModTime) >= 86400) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate the difference between a file's last mod unix time and now
     *
     * @param int $lastMod Last modified unix timestamp of a file
     * @return int
     */
    private function calculateTimeDiff(int $lastMod): int
    {
        $now = time();
        $diff = $now - $lastMod;

        return $diff;
    }

    /**
     * Get GeoIP2 database paths
     *
     * @return array<int, string>
     */
    private function getDatabasePaths(): array
    {
        return [
            $this->config->getAsnDatabasePath(),
            $this->config->getCountryDatabasePath()
        ];
    }
}

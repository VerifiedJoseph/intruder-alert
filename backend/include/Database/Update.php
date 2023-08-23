<?php

namespace Database;

use Config;
use Exception\AppException;
use Helper\File;
use Helper\Output;

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
    
            if ($this->isUpdateDue() === true) {
                Output::text('Updating Geoip2 databases', log: true);

                $client = new \tronovav\GeoIP2Update\Client([
                    'license_key' => $this->config->getMaxMindLicenseKey(),
                    'dir' => $path,
                    'editions' => $this->editions,
                ]);

                $client->run();

                foreach($client->updated() as $message) {
                    Output::text($message, log: true);
                }

                if ($client->errors() != []) {
                    foreach($client->errors() as $message) {
                        Output::text($message, log: true);
                    }

                    throw new AppException('Failed to update Geoip2 databases');
                }
            }
        }
    }

    private function isUpdateDue(): bool
    {
        foreach ($this->editions as $edition) {
            if ($edition == 'GeoLite2-ASN') {
                $file = $this->config->getAsnDatabasePath();
            } else {
                $file = $this->config->getCountryDatabasePath();
            }

            if (File::exists($file) === false) {
                return true;
            }

            $fileModTime = (int) filemtime($file);

            if ($this->calculateTimeDiff($fileModTime) >= 86400) {
                return true;
            }
        }

        return false;
    }

    private function calculateTimeDiff(int $lastMod): int
    {
        $now = time();
        $diff = $now - $lastMod;

        return $diff;
    }
}
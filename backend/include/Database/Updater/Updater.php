<?php

namespace IntruderAlert\Database\Updater;

use IntruderAlert\Config;
use IntruderAlert\Fetch;
use IntruderAlert\Helper\File;
use IntruderAlert\Helper\Output;
use IntruderAlert\Exception\AppException;

class Updater
{
    private Config $config;
    private Fetch $fetch;
    private Helper $helper;

    public function __construct(Config $config, Fetch $fetch, Helper $helper)
    {
        $this->config = $config;
        $this->fetch = $fetch;
        $this->helper = $helper;
    }

    public function run(): void
    {
        if ($this->config->getMaxMindLicenseKey() !== '') {
            if (File::exists($this->config->getGeoIpDatabaseFolder()) === false) {
                mkdir($this->config->getGeoIpDatabaseFolder());
            }

            try {
                foreach ($this->getDatabasePaths() as $edition => $path) {
                    if ($this->helper->checkDatabaseUpdateStatus($path) === true) {
                        Output::text('Updating Geoip2 database: ' . $edition, log: true);

                        $archivePath = $path . '.tar.gz';

                        $downloader = new Downloader($this->fetch, $this->config);
                        $extractor = new Extractor($this->config);

                        $checksumFile = $downloader->getChecksum($edition);
                        $checksum = $extractor->checksum($checksumFile);

                        $downloader->getArchive($edition, $archivePath);
                        $this->helper->checkIntegrity($checksum['hash'], $archivePath);

                        $extractor->archive($archivePath, $edition, $path);

                        Output::text('Updated Geoip2 database: ' . $edition, log: true);
                    }
                }
            } catch (\Exception $err) {
                throw new AppException(sprintf(
                    'Geoip2 database update failed. %s',
                    $err->getMessage()
                ));
            }
        }
    }

    /**
     * Get GeoIP2 database paths
     *
     * @return array<string, string>
     */
    private function getDatabasePaths(): array
    {
        return [
            'GeoLite2-ASN' => $this->config->getAsnDatabasePath(),
            'GeoLite2-Country' => $this->config->getCountryDatabasePath()
        ];
    }
}

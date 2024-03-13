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

    public function __construct(Config $config, Fetch $fetch)
    {
        $this->config = $config;
        $this->fetch = $fetch;
    }

    public function run(): void
    {
        $downloader = new Downloader($this->fetch, $this->config);
        $extractor = new Extractor($this->config);
        $databasePaths = $this->getDatabasePaths();

        if ($this->config->getMaxMindLicenseKey() !== '') {
            try {
                foreach ($databasePaths as $edition => $path) {
                    $tsFile = new TimestampFile($path);

                    if ($tsFile->isOutdated() === true) {
                        Output::text('Updating Geoip2 database: ' . $edition, log: true);

                        $archivePath = $path . '.tar.gz';

                        $checksumFile = $downloader->getChecksum($edition);
                        $checksum = $extractor->checksum($checksumFile);

                        $downloader->getArchive($edition, $archivePath);
                        Helper::checkIntegrity($checksum['hash'], $archivePath);

                        $extractor->archive($archivePath, $edition, $path);
                        $tsFile->update();

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

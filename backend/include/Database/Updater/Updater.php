<?php

namespace IntruderAlert\Database\Updater;

use IntruderAlert\Config;
use IntruderAlert\Fetch;
use IntruderAlert\Logger;
use IntruderAlert\Exception\AppException;

class Updater
{
    private Config $config;
    private Fetch $fetch;
    private Logger $logger;

    public function __construct(Config $config, Fetch $fetch, Logger $logger)
    {
        $this->config = $config;
        $this->fetch = $fetch;
        $this->logger = $logger;
    }

    public function run(): void
    {
        $downloader = new Downloader(
            $this->fetch,
            $this->config,
            $this->logger
        );
        $extractor = new Extractor($this->config);
        $databasePaths = $this->getDatabasePaths();

        if ($this->config->getMaxMindLicenseKey() !== '') {
            try {
                foreach ($databasePaths as $edition => $path) {
                    $tsFile = new TimestampFile($path);

                    if ($tsFile->isOutdated() === true) {
                        $this->logger->addEntry('Updating Geoip2 database: ' . $edition);

                        $archivePath = $path . '.tar.gz';

                        $checksumFile = $downloader->getChecksum($edition);
                        $checksum = $extractor->checksum($checksumFile);

                        $downloader->getArchive($edition, $archivePath);
                        Helper::checkIntegrity($checksum['hash'], $archivePath);

                        $extractor->archive($archivePath, $edition, $path);
                        $tsFile->update();

                        $this->logger->addEntry('Updated Geoip2 database: ' . $edition);
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

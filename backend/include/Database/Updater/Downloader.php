<?php

namespace IntruderAlert\Database\Updater;

use IntruderAlert\Config;
use IntruderAlert\Fetch;
use IntruderAlert\Logger;
use IntruderAlert\Helper\File;
use IntruderAlert\Helper\Output;
use IntruderAlert\Exception\FetchException;
use Exception;

class Downloader
{
    private Fetch $fetch;
    private Url $url;
    private Logger $logger;

    public function __construct(Fetch $fetch, Config $config, Logger $logger)
    {
        $this->logger = $logger;
        $this->fetch = $fetch;
        $this->url = new Url(
            $config->getMaxMindDownloadUrl(),
            $config->getMaxMindLicenseKey()
        );
    }

    /**
     * Download checksum file for an edition
     *
     * @param string $edition Database edition
     */
    public function getChecksum(string $edition): string
    {
        try {
            $this->logger->addEntry('Downloading checksum...');

            $url = $this->url->get($edition, 'tar.gz.sha256');

            return $this->fetch->get($url);
        } catch (FetchException $err) {
            $this->logger->addEntry($err->getMessage());

            throw new Exception(sprintf(
                'Failed to download checksum file: %s',
                $url
            ));
        }
    }

    /**
     * Download database archive
     *
     * @param string $edition Database edition
     */
    public function getArchive(string $edition, string $path): void
    {
        try {
            $this->logger->addEntry('Downloading database...');

            $url = $this->url->get($edition, 'tar.gz');

            $data = $this->fetch->get($url);
            File::write($path, $data);

            //return $this->fetch->get($url);
        } catch (FetchException $err) {
            $this->logger->addEntry($err->getMessage());

            throw new Exception(sprintf(
                'Failed to download database file: %s',
                $url
            ));
        }
    }
}

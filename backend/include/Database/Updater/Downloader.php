<?php

declare(strict_types=1);

namespace IntruderAlert\Database\Updater;

use IntruderAlert\Config;
use IntruderAlert\Fetch;
use IntruderAlert\Logger;
use IntruderAlert\Helper\File;
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
     * @return string Contents of downloaded checksum file
     */
    public function getChecksum(string $edition): string
    {
        $this->logger->info('Downloading checksum...');
        $url = $this->url->get($edition, 'tar.gz.sha256');

        try {
            return $this->fetch->get($url);
        } catch (FetchException $err) {
            $this->logger->info($err->getMessage());

            throw new Exception(sprintf(
                'Failed to download checksum file: %s',
                $url
            ));
        }
    }

    /**
     * Download database archive and save to given path
     *
     * @param string $edition Database edition
     * @param string $path Path to save downloaded database
     */
    public function getArchive(string $edition, string $path): void
    {
        try {
            $this->logger->info('Downloading database...');

            $url = $this->url->get($edition, 'tar.gz');
            $data = $this->fetch->get($url);

            File::write($path, $data);
        } catch (FetchException $err) {
            $this->logger->info($err->getMessage());

            throw new Exception(sprintf(
                'Failed to download database file: %s',
                $url
            ));
        }
    }

    /**
     * Check integrity of the downloaded archive using a sha256 hash
     *
     * @param string $hash Hash checksum from the downloaded checksum file
     * @param string $path Path of archive file
     *
     * @throws Exception if hashes do not match.
     */
    public static function checkArchiveIntegrity(string $hash, string $path): void
    {
        $fileHash = hash_file('sha256', $path);

        if ($fileHash !== $hash) {
            throw new Exception('Integrity check failed: ' . $path);
        }
    }
}

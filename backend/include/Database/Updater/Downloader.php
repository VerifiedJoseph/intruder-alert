<?php

namespace IntruderAlert\Database\Updater;

use IntruderAlert\Config;
use IntruderAlert\Fetch;
use IntruderAlert\Helper\File;
use IntruderAlert\Helper\Output;
use IntruderAlert\Exception\FetchException;
use Exception;

class Downloader
{
    private Fetch $fetch;
    private Config $config;

    public function __construct(Fetch $fetch, Config $config)
    {
        $this->fetch = $fetch;
        $this->config = $config;
    }

    /**
     * Download checksum file for an edition
     *
     * @param string $edition Database edition
     */
    public function getChecksum(string $edition): string
    {
        try {
            Output::text('Downloading checksum...');

            $url = $this->buildUrl(
                $edition,
                $this->config->getMaxMindLicenseKey(),
                'tar.gz.sha256'
            );

            return $this->fetch->get($url);
        } catch (FetchException $err) {
            Output::text($err->getMessage(), log: true);

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
            Output::text('Downloading database...');

            $url = $this->buildUrl(
                $edition,
                $this->config->getMaxMindLicenseKey(),
                'tar.gz'
            );

            $data = $this->fetch->get($url);
            File::write($path, $data);

            //return $this->fetch->get($url);
        } catch (FetchException $err) {
            Output::text($err->getMessage(), log: true);

            throw new Exception(sprintf(
                'Failed to download database file: %s',
                $url
            ));
        }
    }

    /**
     * Build MaxMind download URL
     *
     * @param string $edition Database edition
     * @param string $key License key
     * @param string $suffix Suffix
     * @return string
     */
    private function buildUrl(string $edition, string $key, string $suffix): string
    {
        $parts = [
            'edition_id' => $edition,
            'license_key' => $key,
            'suffix' => $suffix
        ];

        return $this->config->getMaxMindDownloadUrl() . http_build_query($parts);
    }
}

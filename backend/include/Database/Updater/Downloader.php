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
    private Url $url;

    public function __construct(Fetch $fetch, Config $config)
    {
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
            Output::text('Downloading checksum...');

            $url = $this->url->get($edition, 'tar.gz.sha256');

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

            $url = $this->url->get($edition, 'tar.gz');

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
}

<?php

namespace IntruderAlert\Database;

use IntruderAlert\Config;
use IntruderAlert\Fetch;
use IntruderAlert\Helper\File;
use IntruderAlert\Helper\Output;
use IntruderAlert\Exception\AppException;
use IntruderAlert\Exception\FetchException;
use Exception;

class Update
{
    /** @var Config $config */
    private Config $config;

    /** @var string $folder Folder to save database downloads */
    private string $folder = '';

    /** @var string $checksumRegex Regex for extracting checksum details */
    private string $checksumRegex = '/^([A-Za-z0-9]+)\ \ (GeoLite2-(?:[A-Za-z]+)_(?:[0-9]{8})\.tar\.gz)$/';

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->folder = $this->config->getGeoIpDatabaseFolder();
    }

    /**
     * Run updater
    */
    public function run(): void
    {
        if ($this->config->getMaxMindLicenseKey() !== '') {
            if (File::exists($this->folder) === false) {
                mkdir($this->folder);
            }

            try {
                foreach ($this->getDatabasePaths() as $edition => $path) {
                    if ($this->updateRequired($path) === true) {
                        Output::text('Updating Geoip2 database: ' . $edition, log: true);

                        $archivePath = $path . '.tar.gz';
                        $checksumFile = $this->downloadChecksum($edition);
                        $checksum = $this->extractChecksum($checksumFile);

                        $this->downloadDatabase($edition, $archivePath);
                        $this->checkIntegrity($checksum['hash'], $archivePath);
                        $this->extractDatabase($archivePath, $edition, $path);

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
     * Check if a database update is required
     *
     * @param string $path Database path
     * @return boolean
     */
    private function updateRequired(string $path): bool
    {
        if (File::exists($path) === false) {
            return true;
        }

        $fileModTime = (int) filemtime($path);

        if ($this->calculateTimeDiff($fileModTime) >= 86400) {
            return true;
        }

        return false;
    }

    /**
     * Calculate the difference between last modified time of a file and unix time now
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
     * @return array<string, string>
     */
    private function getDatabasePaths(): array
    {
        return [
            'GeoLite2-ASN' => $this->config->getAsnDatabasePath(),
            'GeoLite2-Country' => $this->config->getCountryDatabasePath()
        ];
    }

    /**
     * Download checksum for a database
     *
     * @param string $edition Database edition
     * @return string
     *
     * @throws Exception if downloading checksum file failed
     */
    private function downloadChecksum(string $edition): string
    {
        try {
            Output::text('Downloading checksum...');

            $url = $this->buildUrl(
                $edition,
                $this->config->getMaxMindLicenseKey(),
                'tar.gz.sha256'
            );

            $fetch = new Fetch($this->config->getUseragent());
            $data = $fetch->get($url);

            return $data;
        } catch (FetchException $err) {
            Output::text($err->getMessage(), log: true);

            throw new Exception(sprintf(
                'Failed to download checksum for %s',
                $edition
            ));
        }
    }

    /**
     * Download a database
     *
     * @param string $edition Database edition
     * @param string $path Path to save downloaded archive
     */
    private function downloadDatabase(string $edition, string $path): void
    {
        try {
            Output::text('Downloading database...');

            $url = $this->buildUrl(
                $edition,
                $this->config->getMaxMindLicenseKey(),
                'tar.gz'
            );

            $fetch = new Fetch($this->config->getUseragent());
            $data = $fetch->get($url);

            File::write($path, $data);
        } catch (FetchException $err) {
            Output::text($err->getMessage(), log: true);

            throw new Exception(sprintf(
                'Failed to download database for %s',
                $edition
            ));
        }
    }

    /**
     * Extract checksum from downloaded file
     *
     * @param string $data Checksum file data
     * @return array<string, string> Database checksum and filename
     *
     * @throws Exception if regex failed to extract checksum details from download file
     */
    private function extractChecksum(string $data): array
    {
        if (preg_match($this->checksumRegex, $data, $matches) !== 1) {
            throw new Exception('Checksum extraction failed');
        }

        return [
            'hash' => $matches[1],
            'filename' => $matches[2]
        ];
    }

    /**
     * Check integrity of the archive
     *
     * @param string $checksum
     * @param string $filepath
     *
     * @throws Exception if integrity check failed
     */
    private function checkIntegrity(string $checksum, string $filepath): void
    {
        $fileHash = hash_file('sha256', $filepath);

        if ($fileHash !== $checksum) {
            throw new Exception('Integrity check failed:' . $filepath);
        }
    }

    /**
     * Extract database from archive
     *
     * @param string $archivePath Path of archive file
     * @param string $edition Database edition
     * @param string $path Path to save the database
     *
     * @throws Exception if database not found archive.
     * @throws Exception if moving database failed.
     */
    private function extractDatabase(string $archivePath, string $edition, string $path): void
    {
        $phar = new \PharData($archivePath);
        $phar->extractTo($this->folder, null, true);

        $regex = sprintf('/^%s_([0-9]{8})$/', $edition);
        $directories = new \DirectoryIterator($this->folder);

        foreach ($directories as $directory) {
            if ($directory->isDir() && preg_match($regex, $directory->getBasename())) {
                $filepath = $directory->getPathname() . DIRECTORY_SEPARATOR . $edition . '.mmdb';

                if (file_exists($filepath) === false) {
                    throw new Exception(sprintf(
                        '%s database not found archive: %s',
                        $edition,
                        $archivePath
                    ));
                }

                if (rename($filepath, $path) === false) {
                    throw new Exception(sprintf(
                        'Failed to move database from %s to %s',
                        $filepath,
                        $path
                    ));
                }

                $this->removeDir($directory->getPathname());
            }
        }

        // Remove archive
        unlink($archivePath);
    }

    /**
     * Build MaxMind download Url
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

    /**
     * Remove directory and its contents
     *
     * @param string $path Directory path
     */
    private function removeDir($path): void
    {
        if (is_dir($path) === true) {
            $directory = new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS);
            $items = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($items as $item) {
                if ($item->isDir() === true) {
                    rmdir($item);
                } else {
                    unlink($item);
                }
            }

            rmdir($path);
        }
    }
}

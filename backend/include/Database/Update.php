<?php

namespace IntruderAlert\Database;

use IntruderAlert\Config;
use IntruderAlert\Helper\File;
use IntruderAlert\Helper\Output;
use IntruderAlert\Exception\AppException;
use Exception;

class Update
{
    private Config $config;

    private string $folder = 'data/geoip2';

    private string $apiUrl = 'https://download.maxmind.com/app/geoip_download?edition_id=%s&license_key=%s&suffix=%s';

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function run(): void
    {
        if ($this->config->getMaxMindLicenseKey() !== '') {
            $folder = $this->config->getPath($this->folder);

            if (File::exists($folder) === false) {
                mkdir($folder);
            }

            try {
                foreach ($this->getDatabasePaths() as $edition => $path) {
                    if ($this->updateRequired($path) === true) {
                        Output::text('Updating Geoip2 database: ' . $edition, log: true);

                        $archivePath = $path . '.tar.gz';
                        $checksum = $this->downloadChecksum($edition);

                        $this->downloadDatabase($edition, $archivePath);
                        $this->checkIntegrity($checksum['hash'], $archivePath);
                        $this->extractDatabase($archivePath, $edition, $path);

                        Output::text('Updated Geoip2 database: ' . $edition, log: true);
                    }
                }
            } catch (\Exception $err) {
                throw new AppException(sprintf(
                    'Update database failed. %s',
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
     * @return array<string, string> Database checksum and filename
     */
    private function downloadChecksum(string $edition): array
    {
        $regex = '/^([A-Za-z0-9]+)\ \ (GeoLite2-(?:[A-Za-z]+)_(?:[0-9]{8})\.tar\.gz)$/';
        $url = sprintf(
            $this->apiUrl,
            $edition,
            $this->config->getMaxMindLicenseKey(),
            'tar.gz.sha256'
        );

        $data = $this->fetch($url);

        if (preg_match($regex, $data, $matches) !== 1) {
            throw new Exception('Failed extract checksum from downloaded file.');
        }

        return [
            'hash' => $matches[1],
            'filename' => $matches[2]
        ];
    }

    /**
     * Download a database
     *
     * @param string $edition Database edition
     * @param string $path Path to save downloaded archive
     */
    private function downloadDatabase(string $edition, string $path): void
    {
        $url = sprintf(
            $this->apiUrl,
            $edition,
            $this->config->getMaxMindLicenseKey(),
            'tar.gz'
        );

        $data = $this->fetch($url);

        File::write($path, $data);
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

                rename($filepath, $path);

                $this->removeDir($directory->getPathname());
            }
        }

        // Remove archive
        unlink($archivePath);
    }

    /**
     * Fetch URL
     *
     * @param string $url URL
     * @return string
     *
     * @throws Exception if an CURL error occurred
     * @throws Exception if request returned non-200 status code
     */
    private function fetch(string $url): string
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->config->getUseragent()
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($error !== '') {
            throw new Exception($error);
        }

        if ($statusCode !== 200) {
            throw new Exception(sprintf(
                'Request failed. Returned HTTP %s',
                $statusCode
            ));
        }

        return (string) $response;
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

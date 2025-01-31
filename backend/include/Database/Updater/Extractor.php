<?php

declare(strict_types=1);

namespace IntruderAlert\Database\Updater;

use IntruderAlert\Config;
use IntruderAlert\Logger;
use Exception;

class Extractor
{
    /** @var string $checksumRegex Regex for extracting checksum details */
    private string $checksumRegex = '/^([A-Za-z0-9]+)\ \ (GeoLite2-(?:[A-Za-z]+)_(?:[0-9]{8})\.tar\.gz)$/';

    private Config $config;
    private Logger $logger;

    public function __construct(Config $config, Logger $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Extract checksum from downloaded file
     *
     * @param string $data Checksum file data
     * @return array<string, string> Database checksum and filename
     *
     * @throws Exception if regex failed to extract checksum details from download file
     */
    public function checksum(string $data): array
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
     * Extract database from archive
     *
     * @param string $archivePath Path of archive file
     * @param string $edition Database edition
     * @param string $path Path to save the database
     *
     * @throws Exception if database not found in archive.
     * @throws Exception if moving database failed.
     */
    public function archive(string $archivePath, string $edition, string $path): void
    {
        $phar = new \PharData($archivePath);
        $phar->extractTo($this->config->getGeoIpDatabaseFolder(), null, true);

        $regex = sprintf('/^%s_([0-9]{8})$/', $edition);
        $directories = new \DirectoryIterator($this->config->getGeoIpDatabaseFolder());

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

                $this->logger->debug(sprintf(
                    'Moving database file from %s to %s',
                    $filepath,
                    $path
                ));

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
     * Remove directory and its contents
     *
     * @param string $path Directory path
     */
    private function removeDir(string $path): void
    {
        if (is_dir($path) === true) {
            $this->logger->debug('Removing directory: ' . $path);

            $directory = new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS);
            $items = new \RecursiveIteratorIterator($directory, \RecursiveIteratorIterator::CHILD_FIRST);

            /** @var \SplFileInfo $item */
            foreach ($items as $item) {
                if ($item->isDir() === false) {
                    unlink($item->getPathname());
                }
            }

            rmdir($path);
        }
    }
}

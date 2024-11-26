<?php

namespace IntruderAlert\Database\Updater;

use IntruderAlert\Helper\File;

/**
 * Class to read and write database timestamp file
 */
class TimestampFile
{
    /** @var string $path Timestamp filepath */
    private string $path;

    /** @var int $timestamp Timestamp from file as an Unix timestamp */
    private int $timestamp;

    /**
     * @param string $path Database filepath
     */
    public function __construct($path)
    {
        $this->path = $this->convertPath($path);
        $this->timestamp = $this->loadFile();
    }

    /**
     * Return timestamp from file as Unix timestamp
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * Check if the timestamp is 24 or more hours old
     */
    public function isOutdated(): bool
    {
        if ($this->calculateTimeDiff($this->timestamp) >= 86400) {
            return true;
        }

        return false;
    }

    /**
     * Write current time to file
     */
    public function update(): void
    {
        $dt = new \DateTimeImmutable();
        File::write($this->path, $dt->format('Y-m-d H:i:s'));
    }

    /**
     * Convert path from database to timestamp file
     *
     * @param string $path Database filepath
     */
    private function convertPath(string $path): string
    {
        return str_replace('mmdb', 'timestamp', $path);
    }

    /**
     * Get timestamp from file
     *
     * @return int
     */
    private function loadFile(): int
    {
        if (File::exists($this->path) === false) {
            return 0;
        }

        return (int) strtotime(File::read($this->path));
    }

    /**
     * Calculate the difference between a unix timestamp and unix time now
     *
     * @param int $timestamp Timestamp from the file as a Unix timestamp
     * @return int
     */
    private function calculateTimeDiff(int $timestamp): int
    {
        $now = time();
        $diff = $now - $timestamp;

        return $diff;
    }
}

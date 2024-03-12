<?php

namespace IntruderAlert\Database\Updater;

use IntruderAlert\Helper\File;
use Exception;

class Helper
{
    /**
     * Check file integrity using a sha256 hash
     *
     * @param $hash Hash checksum
     * @param $filepath
     */
    public function checkIntegrity(string $hash, string $filepath): void
    {
        $fileHash = hash_file('sha256', $filepath);

        if ($fileHash !== $hash) {
            throw new Exception('Integrity check failed:' . $filepath);
        }
    }

    public function checkDatabaseUpdateStatus(string $path): bool
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
}

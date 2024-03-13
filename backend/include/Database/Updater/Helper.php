<?php

namespace IntruderAlert\Database\Updater;

use Exception;

class Helper
{
    /**
     * Check file integrity using a sha256 hash
     *
     * @param $hash Hash checksum
     * @param $filepath
     */
    public static function checkIntegrity(string $hash, string $filepath): void
    {
        $fileHash = hash_file('sha256', $filepath);

        if ($fileHash !== $hash) {
            throw new Exception('Integrity check failed:' . $filepath);
        }
    }
}

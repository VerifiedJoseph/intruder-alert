<?php

namespace IntruderAlert\Database\Updater;

use Exception;

class Integrity
{
    public static function check(string $checksum, string $filepath)
    {
        $fileHash = hash_file('sha256', $filepath);

        if ($fileHash !== $checksum) {
            throw new Exception('Integrity check failed:' . $filepath);
        }
    }
}

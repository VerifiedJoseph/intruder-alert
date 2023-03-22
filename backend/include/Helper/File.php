<?php

namespace Helper;

use Exception\AppException;

final class File
{
    /**
     * Read a file
     *
     * @param string $path File path
     * @return string $contents File contents
     *
     * @throws AppException if file was not opened.
     * @throws AppException if file was not read.
     */
    public static function read(string $path): string
    {
        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new AppException('File not opened: ' . $path);
        }

        $contents = fread($handle, (int) filesize($path));

        if ($contents === false) {
            throw new AppException('File not read: ' . $path);
        }

        fclose($handle);

        return $contents;
    }

    /**
     * Write a file
     *
     * @param string $path File path
     * @param string $data Data to write
     *
     * @throws AppException if file was not opened.
     * @throws AppException if data was not written to file.
     */
    public static function write(string $path, string $data): void
    {
        $handle = fopen($path, 'w');

        if ($handle === false) {
            throw new AppException('File not opened: ' . $path);
        }

        $status = fwrite($handle, $data);

        if ($status === false) {
            throw new AppException('File not written: ' . $path);
        }

        fclose($handle);
    }

    /**
     * Checks whether a file exists
     *
     * @param string $path File path
     * @return bool
     */
    public static function exists(string $path)
    {
        clearstatcache();

        if (file_exists($path) === false) {
            return false;
        }

        return true;
    }
}

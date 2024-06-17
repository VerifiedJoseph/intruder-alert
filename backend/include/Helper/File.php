<?php

namespace IntruderAlert\Helper;

use IntruderAlert\Exception\AppException;

final class File
{
    /**
     * Open a file handler
     *
     * @param string $path File path
     * @param string $mode Mode
     *
     * @throws AppException if file was not opened.
     */
    public static function open(string $path, string $mode): mixed
    {
        $handle = @fopen($path, $mode);

        if ($handle === false) {
            throw new AppException('File not opened: ' . $path);
        }

        return $handle;
    }

    /**
     * Read a file
     *
     * @param string $path File path
     * @return string $contents File contents
     *
     * @throws AppException if file was not opened.
     * @throws AppException if file was not read.
     * @throws AppException if file is empty.
     */
    public static function read(string $path): string
    {
        $handle = File::open($path, 'r');
        $size = (int) filesize($path);

        if ($size === 0) {
            throw new AppException('File is empty: ' . $path);
        }

        $contents = @fread($handle, $size);

        if ($contents === false || $contents === '') {
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
        $handle = File::open($path, 'w');
        $status = @fwrite($handle, $data);

        if ($status === false || $status === 0) {
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
        return file_exists($path);
    }
}

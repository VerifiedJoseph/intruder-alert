<?php

use PHPUnit\Framework\TestCase as TestCase;
use IntruderAlert\Logger;

abstract class AbstractTestCase extends TestCase
{
    protected static string $tempPath = '';
    protected static Logger $logger;

    public static function setUpBeforeClass(): void
    {
        self::$logger = new Logger('UTC');
        self::$tempPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'intruder-alert-tests' . DIRECTORY_SEPARATOR;

        if (file_exists(self::$tempPath) === false) {
            mkdir(self::$tempPath);
        }
    }

    /**
     * Remove directory and its contents
     *
     * @param string $path Directory path
     */
    protected static function removeDir($path): void
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

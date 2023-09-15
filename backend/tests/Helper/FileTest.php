<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Helper\File;
use IntruderAlert\Exception\AppException;

class FileTest extends TestCase
{
    private static string $tempFilePath;

    public static function setUpBeforeClass(): void
    {
        self::$tempFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'text.txt';

        file_put_contents(self::$tempFilePath, 'Hello World');
    }

    /**
     * Test exists()
     */
    public function testExists(): void
    {
        self::assertEquals(true, File::exists(self::$tempFilePath));
    }

    /**
     * Test exists() when file does not exist.
     */
    public function testExistsFalse(): void
    {
        self::assertEquals(false, File::exists('no-file-exists.yaml'));
    }

    /**
     * Test read()
     */
    public function testRead(): void
    {
        self::assertEquals('Hello World', File::read(self::$tempFilePath));
    }

    /**
     * Test read() file not opened exception.
     *
     * '@' is used suppress notices and errors from fopen()
     */
    public function testReadNotOpenedException(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('File not opened');

        @File::read('no-file-exists.txt');
    }

    /**
     * Test write()
     */
    public function testWrite(): void
    {
        $data = 'Hello Word from PHP Unit';

        File::write(self::$tempFilePath, $data);

        self::assertEquals($data, File::read(self::$tempFilePath));
    }

    public static function tearDownAfterClass(): void
    {
        unlink(self::$tempFilePath);
    }
}

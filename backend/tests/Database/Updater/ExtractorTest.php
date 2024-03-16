<?php

use PHPUnit\Framework\TestCase;
use MockFileSystem\MockFileSystem as mockfs;
use IntruderAlert\Config;
use IntruderAlert\Database\Updater\Extractor;

class ExtractorTest extends TestCase
{
    private static string $tempFolder = '';
    private static Config $config;

    public static function setUpBeforeClass(): void
    {
        self::$tempFolder = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'intruder-alert-tests';

        /** @var \PHPUnit\Framework\MockObject\Stub&Config */
        $config = self::createStub(Config::class);
        $config->method('getGeoIpDatabaseFolder')->willReturn(self::$tempFolder);
        self::$config = $config;
    }

    public function setup(): void
    {
        mkdir(self::$tempFolder);
        mkdir(self::$tempFolder . DIRECTORY_SEPARATOR . 'has-mmdb');
        mkdir(self::$tempFolder . DIRECTORY_SEPARATOR . 'no-mmdb');
    }

    public function tearDown(): void
    {
        $this->removeDir(self::$tempFolder);
    }

    /**
     * Test `checksum()`
     */
    public function testChecksum(): void
    {
        $expected = [
            'hash' => 'd8578edf8458ce06fbc5bb76a58c5ca4',
            'filename' => 'GeoLite2-ASN_19700101.tar.gz'
        ];

        $extractor = new Extractor(new Config());
        $actual = $extractor->checksum('d8578edf8458ce06fbc5bb76a58c5ca4  GeoLite2-ASN_19700101.tar.gz');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test `checksum()` with invalid checksum string
     */
    public function testChecksumFailure(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Checksum extraction failed');

        $extractor = new Extractor(new Config());
        $extractor->checksum('GeoLite2-ANS_19700101.tar.gz');
    }

    /**
     * Test `archive()`
     */
    public function testArchive(): void
    {
        // Path of test archive
        $archivePath = self::$tempFolder . DIRECTORY_SEPARATOR . 'has-mmdb/GeoLite2-ASN_19700101.tar.gz';
        // Path of the extracted archive folder
        $extractedArchivePath = self::$tempFolder . DIRECTORY_SEPARATOR . 'GeoLite2-ASN_19700101';
        // Path of extracted database
        $extractedDatabasePath = self::$tempFolder . DIRECTORY_SEPARATOR . 'GeoLite2-ASN.mmdb';

        // Copy archive to test folder
        copy('./backend/tests/files/tar/has-mmdb/GeoLite2-ASN_19700101.tar.gz', $archivePath);

        $extractor = new Extractor(self::$config);
        $extractor->archive($archivePath, 'GeoLite2-ASN', $extractedDatabasePath);

        $this->assertFileExists($extractedDatabasePath);
        $this->assertFileDoesNotExist($extractedArchivePath);
    }

    /**
     * Test `archive()` with rename failure
     */
    public function testArchiveRenameFailure(): void
    {
        // Path of test archive
        $archivePath = self::$tempFolder . DIRECTORY_SEPARATOR . 'has-mmdb/GeoLite2-ASN_19700101.tar.gz';

        // Copy archive to test folder
        copy('./backend/tests/files/tar/has-mmdb/GeoLite2-ASN_19700101.tar.gz', $archivePath);

        mockfs::create();
        $file = mockfs::getUrl('/GeoLite2-ASN.mmdb');

        stream_context_set_default([
            'mfs' => [
                'rename_fail' => true,
            ]
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed to move database');

        $extractor = new Extractor(self::$config);
        @$extractor->archive($archivePath, 'GeoLite2-ASN', $file);
    }

    /**
     * Test `archive()` with no database file in the tar archive
     */
    public function testArchiveNoDatabaseFile(): void
    {
        // Path of test archive
        $archivePath = self::$tempFolder . DIRECTORY_SEPARATOR . 'no-mmdb/GeoLite2-ASN_19700101.tar.gz';
        // Path of extracted database
        $extractedDatabasePath = self::$tempFolder . DIRECTORY_SEPARATOR . 'GeoLite2-ASN.mmdb';

        // Copy archive to test folder
        copy('./backend/tests/files/tar/no-mmdb/GeoLite2-ASN_19700101.tar.gz', $archivePath);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('GeoLite2-ASN database not found archive');

        $extractor = new Extractor(self::$config);
        $extractor->archive($archivePath, 'GeoLite2-ASN', $extractedDatabasePath);
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

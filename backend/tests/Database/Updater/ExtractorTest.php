<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Config;
use IntruderAlert\Database\Updater\Extractor;

class ExtractorTest extends TestCase
{
    /**
     * Test `checksum()`
     */
    public function testChecksum(): void
    {
        $expected = [
            'hash' => 'd8578edf8458ce06fbc5bb76a58c5ca4',
            'filename' => 'GeoLite2-ANS_19700101.tar.gz'
        ];

        $extractor = new Extractor(new Config());
        $actual = $extractor->checksum('d8578edf8458ce06fbc5bb76a58c5ca4  GeoLite2-ANS_19700101.tar.gz');

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
        // Path of test folder
        $folder = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'intruder-alert-tests';
        // Path of test archive
        $archivePath = $folder . DIRECTORY_SEPARATOR . 'GeoLite2-ASN_19700101.tar.gz';
        // Path of the extracted archive folder
        $extractedArchivePath = $folder . DIRECTORY_SEPARATOR . 'GeoLite2-ASN_19700101';
        // Path of extracted database
        $extractedDatabasePath = $folder . DIRECTORY_SEPARATOR . 'GeoLite2-ASN.mmdb';

        // Copy archive to test folder
        copy('backend/tests/files/tar/has-mmdb/GeoLite2-ASN_19700101.tar.gz', $archivePath);

        $config = $this->createStub(Config::class);
        $config->method('getGeoIpDatabaseFolder')->willReturn($folder);

        $extractor = new Extractor($config);
        $extractor->archive($archivePath, 'GeoLite2-ASN', $extractedDatabasePath);

        $this->assertFileExists($extractedDatabasePath);
        $this->assertFileDoesNotExist($extractedArchivePath);

        unlink($extractedDatabasePath);
    }
}

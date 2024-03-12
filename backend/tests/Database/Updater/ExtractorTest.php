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
}

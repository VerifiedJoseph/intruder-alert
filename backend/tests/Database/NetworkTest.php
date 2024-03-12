<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Database\Network;
use IntruderAlert\Helper\Output;

class NetworkTest extends TestCase
{
    /** @var string $path Database path */
    private string $path = './backend/tests/files/mmdb/GeoLite2-ASN-Test.mmdb';

    public static function setUpBeforeClass(): void
    {
        Output::disableQuiet();
    }

    /**
     * Test `lookup()`
     */
    public function testLookup(): void
    {
        $expected = [
            'name' => 'Google Inc.',
            'number' => 15169,
            'subnet' => '1.0.0.0/24'
        ];

        $database = new Network($this->path);

        $this->assertEquals(
            $expected,
            $database->lookup('1.0.0.1')
        );
    }

    /**
     * Test `lookup()` with an Address not in the database
     */
    public function testLookupAddressNotFound(): void
    {
        $this->expectOutputRegex('/Address not found in GeoIP2 ASN database/');

        $expected = [
            'name' => 'Unknown',
            'number' => 'Unknown',
            'subnet' => 'Unknown'
        ];

        $database = new Network($this->path);

        $this->assertEquals(
            $expected,
            $database->lookup('127.0.0.1')
        );
    }
}

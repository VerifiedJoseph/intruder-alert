<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Database\Network;
use IntruderAlert\Helper\Output;

class NetworkTest extends TestCase
{
    private static Network $database;

    public static function setUpBeforeClass(): void
    {
        self::$database = new Network('./backend/tests/files/mmdb/GeoLite2-ASN-Test.mmdb');
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

        $this->assertEquals(
            $expected,
            self::$database->lookup('1.0.0.1')
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

        $this->assertEquals(
            $expected,
            self::$database->lookup('127.0.0.1')
        );
    }
}

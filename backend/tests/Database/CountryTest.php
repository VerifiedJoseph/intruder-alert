<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Database\Country;

class CountryTest extends TestCase
{
    private static Country $database;

    public static function setUpBeforeClass(): void
    {
        self::$database = new Country('./backend/tests/files/mmdb/GeoLite2-Country-Test.mmdb');
    }

    /**
     * Test `lookup()`
     */
    public function testLookup(): void
    {
        $expected = [
            'country' => [
                'name' => 'United Kingdom',
                'code' => 'GB'
            ],
            'continent' => [
                'name' => 'Europe',
                'code' => 'EU'
            ]
        ];

        $this->assertEquals(
            $expected,
            self::$database->lookup('81.2.69.144')
        );
    }

    /**
     * Test `lookup()` with an Address not in the database
     */
    public function testLookupAddressNotFound(): void
    {
        $this->expectOutputRegex('/ Address not found in GeoIP2 country database/');

        $expected = [
            'country' => [
                'name' => 'Unknown',
                'code' => 'Unknown'
            ],
            'continent'  => [
                'name' => 'Unknown',
                'code' => 'Unknown'
            ]
        ];

        $this->assertEquals(
            $expected,
            self::$database->lookup('127.0.0.1')
        );
    }
}

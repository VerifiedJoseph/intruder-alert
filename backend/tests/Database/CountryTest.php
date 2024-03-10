<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Database\Country;

class CountryTest extends TestCase
{
    private static Country $database;

    static function setUpBeforeClass(): void
    {
        self::$database = new Country('./backend/tests/files/mmdb/GeoLite2-Country-Test.mmdb');
    }

    function testLookup(): void
    {
        $response = [
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
            $response,
            self::$database->lookup('81.2.69.144')
        );
    }

    function testLookupAddressNotFound(): void
    {
        $this->expectOutputRegex('/ Address not found in GeoIP2 country database/');

        $response = [
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
            $response,
            self::$database->lookup('127.0.0.1')
        );
    }
}

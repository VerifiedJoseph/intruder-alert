<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use IntruderAlert\Database\Country;
use IntruderAlert\Logger;

#[CoversClass(Country::class)]
#[UsesClass(Logger::class)]
#[UsesClass(IntruderAlert\Database\AbstractDatabase::class)]
#[UsesClass(IntruderAlert\Helper\Output::class)]
#[UsesClass(GeoIp2\Exception\AddressNotFoundException::class)]
class CountryTest extends AbstractTestCase
{
    /** @var string $path Database path */
    private string $path = './backend/tests/files/mmdb/GeoLite2-Country-Test.mmdb';

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

        $database = new Country($this->path, new Logger());

        $this->assertEquals(
            $expected,
            $database->lookup('81.2.69.144')
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

        $database = new Country($this->path, new Logger());

        $this->assertEquals(
            $expected,
            $database->lookup('127.0.0.1')
        );
    }
}

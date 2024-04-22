<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use IntruderAlert\Database\Network;
use IntruderAlert\Logger;

#[CoversClass(Network::class)]
#[UsesClass(Logger::class)]
#[UsesClass(IntruderAlert\Database\AbstractDatabase::class)]
#[UsesClass(IntruderAlert\Helper\Output::class)]
#[UsesClass(GeoIp2\Exception\AddressNotFoundException::class)]
class NetworkTest extends AbstractTestCase
{
    /** @var string $path Database path */
    private string $path = './backend/tests/files/mmdb/GeoLite2-ASN-Test.mmdb';

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

        $database = new Network($this->path, new Logger());

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

        $database = new Network($this->path, new Logger());

        $this->assertEquals(
            $expected,
            $database->lookup('127.0.0.1')
        );
    }
}

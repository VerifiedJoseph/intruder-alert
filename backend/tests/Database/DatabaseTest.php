<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Database\Database;

class DatabaseTest extends TestCase
{
    private static Database $database;

    /**
     * Test `__construct`
     */
    public function testConstruct(): void
    {
        $path = './backend/tests/files/mmdb/GeoLite2-ASN-Test.mmdb';

        $database = new class ($path) extends Database {
            public function lookup(string $address): array
            {
                return [];
            }

            public function getReader(): \GeoIp2\Database\Reader
            {
                return $this->reader;
            }
        };

        $this->assertInstanceOf(
            \GeoIp2\Database\Reader::class,
            $database->getReader()
        );
    }
}

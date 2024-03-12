<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\List\Addresses;

class AddressesTest extends TestCase
{
    private static $data;
    private static $expected;
    private static Addresses $listClass;

    public static function setUpBeforeClass(): void
    {
        self::$listClass = new Addresses();

        self::$data = json_decode(
            file_get_contents('./backend/tests/files/list-data.json'),
            associative: true
        );

        self::$expected = json_decode(
            file_get_contents('./backend/tests/files/lists/expected-address-list.json'),
            associative: true
        );
    }

    /**
     * Test `addIp()`
     */
    public function testAddIp(): void
    {
        $this->expectNotToPerformAssertions();

        foreach (self::$data['events'] as $item) {
            self::$listClass->addIp($item);
        }
    }

    /**
     * Test `get()`
     * 
     * @depends testAddIp
     */
    public function testGet(): void
    {
        $this->assertEquals(self::$listClass->get(), self::$expected);
    }

    /**
     * test `getTotalBans()`
     * 
     * @depends testAddIp
     */
    public function testGetTotalBans(): void
    {
        $this->assertEquals(7, self::$listClass->getTotalBans());
    }
}
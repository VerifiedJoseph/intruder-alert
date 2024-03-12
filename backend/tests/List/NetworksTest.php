<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\List\Networks;

class NetworksTest extends TestCase
{
    private static $data;
    private static $expected;
    private static Networks $listClass;

    public static function setUpBeforeClass(): void
    {
        self::$listClass = new Networks();

        self::$data = json_decode(
            file_get_contents('./backend/tests/files/list-data.json'),
            associative: true
        );

        self::$expected = json_decode(
            file_get_contents('./backend/tests/files/lists/expected-network-list.json'),
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
}

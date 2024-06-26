<?php

use PHPUnit\Framework\Attributes\CoversClass;
use IntruderAlert\List\Addresses;

#[CoversClass(Addresses::class)]
#[CoversClass(IntruderAlert\List\AbstractList::class)]
class AddressesTest extends AbstractTestCase
{
    private static Addresses $listClass;

    /** @var array<mixed> */
    private static $data;

    /** @var array<mixed> */
    private static $expected;

    public static function setUpBeforeClass(): void
    {
        self::$listClass = new Addresses();

        self::$data = json_decode(
            (string)
            file_get_contents('./backend/tests/files/list-data.json'),
            associative: true
        );

        self::$expected = json_decode(
            (string)
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
     * Test `getCount()`
     *
     * @depends testAddIp
     */
    public function testGetCount(): void
    {
        $this->assertEquals(6, self::$listClass->getCount());
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

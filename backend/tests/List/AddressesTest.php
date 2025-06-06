<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Depends;
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
    #[DoesNotPerformAssertions]
    public function testAddIp(): void
    {
        foreach (self::$data['events'] as $item) {
            self::$listClass->addIp($item);
        }
    }

    /**
     * Test `get()`
     */
    #[Depends('testAddIp')]
    public function testGet(): void
    {
        $this->assertEquals(self::$listClass->get(), self::$expected);
    }

    /**
     * Test `getCount()`
     */
    #[Depends('testAddIp')]
    public function testGetCount(): void
    {
        $this->assertEquals(6, self::$listClass->getCount());
    }

    /**
     * test `getTotalBans()`
     */
    #[Depends('testAddIp')]
    public function testGetTotalBans(): void
    {
        $this->assertEquals(7, self::$listClass->getTotalBans());
    }
}

<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Depends;
use IntruderAlert\List\Subnets;

#[CoversClass(Subnets::class)]
#[CoversClass(IntruderAlert\List\AbstractList::class)]
class SubnetsTest extends AbstractTestCase
{
    private static Subnets $listClass;

    /** @var array<mixed> */
    private static $data;

    /** @var array<mixed> */
    private static $expected;

    public static function setUpBeforeClass(): void
    {
        self::$listClass = new Subnets();

        self::$data = json_decode(
            (string)
            file_get_contents('./backend/tests/files/list-data.json'),
            associative: true
        );

        self::$expected = json_decode(
            (string)
            file_get_contents('./backend/tests/files/lists/expected-subnet-list.json'),
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
}

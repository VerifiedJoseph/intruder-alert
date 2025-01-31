<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\UsesClass;
use IntruderAlert\List\Dates;

#[CoversClass(Dates::class)]
#[UsesClass(IntruderAlert\List\AbstractList::class)]
class DatesTest extends AbstractTestCase
{
    private static Dates $listClass;

    /** @var array<mixed> */
    private static $data;

    /** @var array<mixed> */
    private static $expected;

    public static function setUpBeforeClass(): void
    {
        self::$listClass = new Dates();

        self::$data = json_decode(
            (string)
            file_get_contents('./backend/tests/files/list-data.json'),
            associative: true
        );

        self::$expected = json_decode(
            (string)
            file_get_contents('./backend/tests/files/lists/expected-date-list.json'),
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

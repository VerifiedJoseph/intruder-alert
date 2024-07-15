<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Depends;
use IntruderAlert\List\Continents;

#[CoversClass(Continents::class)]
#[CoversClass(IntruderAlert\List\AbstractList::class)]
class ContinentsTest extends AbstractTestCase
{
    private static Continents $listClass;

    /** @var array<mixed> */
    private static $data;

    /** @var array<mixed> */
    private static $expected;

    public static function setUpBeforeClass(): void
    {
        self::$listClass = new Continents();

        self::$data = json_decode(
            (string)
            file_get_contents('./backend/tests/files/list-data.json'),
            associative: true
        );

        self::$expected = json_decode(
            (string)
            file_get_contents('./backend/tests/files/lists/expected-continent-list.json'),
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

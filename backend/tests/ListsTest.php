<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use IntruderAlert\Lists;

#[CoversClass(Lists::class)]
#[UsesClass(IntruderAlert\List\AbstractList::class)]
#[UsesClass(IntruderAlert\List\Addresses::class)]
#[UsesClass(IntruderAlert\List\Continents::class)]
#[UsesClass(IntruderAlert\List\Countries::class)]
#[UsesClass(IntruderAlert\List\Dates::class)]
#[UsesClass(IntruderAlert\List\Jails::class)]
#[UsesClass(IntruderAlert\List\Networks::class)]
#[UsesClass(IntruderAlert\List\Subnets::class)]
class ListsTest extends AbstractTestCase
{
    private static Lists $lists;

    /** @var array<mixed> */
    private static $data;

    public static function setUpBeforeClass(): void
    {
        self::$data = json_decode(
            (string)
            file_get_contents('./backend/tests/files/list-data.json'),
            associative: true
        );
    }

    /**
     * Test `addIp()`
     */
    public function testAddIp(): void
    {
        $this->expectNotToPerformAssertions();

        self::$lists = new Lists();

        foreach (self::$data['events'] as $item) {
            self::$lists->addIp($item);
        }
    }

    /**
     * Test `get()`
     *
     * @depends testAddIp
     */
    public function testGet(): void
    {
        $listNames = ['address', 'date', 'jail', 'network', 'subnet', 'country', 'continent'];
        $data = self::$lists->get();

        foreach ($listNames as $name) {
            $filename = sprintf('./backend/tests/files/lists/expected-%s-list.json', $name);
            $expected = json_decode(
                (string)
                file_get_contents($filename),
                associative: true
            );

            $this->assertArrayHasKey($name, $data);
            $this->assertEquals($expected, $data[$name]);
        }
    }

    /**
     * Test `getCounts()`
     *
     * @depends testAddIp
     */
    public function testGetCounts(): void
    {
        $countNames = ['totalBans', 'address', 'date', 'jail', 'network', 'subnet', 'country', 'continent'];
        $data = self::$lists->getCounts();

        foreach ($countNames as $name) {
            $this->assertArrayHasKey($name, $data);
            $this->assertIsInt($data[$name]);
        }
    }
}

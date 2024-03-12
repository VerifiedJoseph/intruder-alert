<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Lists;

class ListsTest extends TestCase
{
    private static Lists $lists;

    /** @var array<mixed> */
    private static $data;

    public static function setUpBeforeClass(): void
    {
        self::$lists = new Lists();

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

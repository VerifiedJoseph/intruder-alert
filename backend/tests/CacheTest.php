<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Cache;

class CacheTest extends TestCase
{
    private static string $path = './backend/tests/files/cache-data.json';

    /** @var array<string, mixed> $data Data from cache-data.json */
    private static array $data = [];

    private static string $tempCacheFilePath = '';
    private static string $tempExpiredCacheFilePath = '';

    /** @var array<string, mixed> $testItem Single cache test item */
    private static array $testItem = [
        'address' => '127.0.0.1',
        'version' => 4,
        'network' => [
            'name' => 'Home Sweet Home',
            'number' => '1245'
        ],
        'country' => [
            "name" => "United Kingdom",
            "code" => "GB"
        ],
        'continent' => [
            "name" => "Europe",
            "code" => "EU"
        ]
    ];

    public static function setUpBeforeClass(): void
    {
        // Load data
        $contents = (string) file_get_contents(self::$path);
        self::$data = json_decode($contents, associative: true);

        // Create temp cache files
        self::createTempCacheFiles();
    }

    public static function tearDownAfterClass(): void
    {
        // Remove temp cache files
        unlink(self::$tempCacheFilePath);
        unlink(self::$tempExpiredCacheFilePath);
    }

    /**
     * Test `hasItem()`
     */
    public function testHasItem(): void
    {
        $cache = new Cache(self::$tempCacheFilePath);

        $this->assertTrue($cache->hasItem('185.15.59.224'));
        $this->assertFalse($cache->hasItem('127.0.0.1'));
    }

    /**
     * Test `getItem()`
     */
    public function testGetItem(): void
    {
        $cache = new Cache(self::$tempCacheFilePath);

        $this->assertEquals(
            self::$data['items']['2001:67c:930::1'],
            $cache->getItem('2001:67c:930::1')
        );
    }

    /**
     * Test `AddItem()`
     */
    public function testAddItem(): void
    {
        $cache = new Cache(self::$tempCacheFilePath);
        $cache->addItem(self::$testItem);

        $item = $cache->getItem(self::$testItem['address']);

        $this->assertEquals(self::$testItem, $item);
    }

    /**
     * Test `save()`
     */
    public function testSave(): void
    {
        $cache = new Cache(self::$tempCacheFilePath);
        $cache->addItem(self::$testItem);
        $cache->save();

        $data = self::$data;
        $data[self::$testItem['address']] = self::$testItem;
        $address = self::$testItem['address'];

        // Load data from updated cache file
        $contents = (string) file_get_contents(self::$tempCacheFilePath);
        $cacheData = json_decode($contents, associative: true);

        $this->assertGreaterThan(0, $cacheData['expires']);
        $this->assertArrayHasKey($address, $cacheData['items']);
        $this->assertCount(3, $cacheData['items']);
        $this->assertEquals($data[$address], $cacheData['items'][$address]);
    }

    /**
     * Test class with expired cache data
     */
    public function testClassWithExpiredData(): void
    {
        $cache = new Cache(self::$tempExpiredCacheFilePath);

        $this->assertFalse($cache->hasItem('185.15.59.224'));
        $this->assertFalse($cache->hasItem('2001:67c:930::1'));
        $this->assertEquals([], $cache->getItem('2001:67c:930::1'));
    }

    /**
     * Create temp cache files
     */
    private static function createTempCacheFiles(): void
    {
        // Create temp cache data file with unexpired unix time value
        self::$tempCacheFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'cache-data.json';

        $data = self::$data;
        $data['expires'] = time() + 300;
        file_put_contents(self::$tempCacheFilePath, json_encode($data));

        // Create temp cache data file with expired unix time value
        self::$tempExpiredCacheFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'expired-cache-data.json';
        file_put_contents(self::$tempExpiredCacheFilePath, json_encode(self::$data));
    }
}

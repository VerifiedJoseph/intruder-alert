<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use MockFileSystem\MockFileSystem as mockfs;
use IntruderAlert\Config;
use IntruderAlert\App\Backend;

#[CoversClass(Backend::class)]
#[CoversClass(IntruderAlert\App\App::class)]
#[UsesClass(Config::class)]
#[UsesClass(IntruderAlert\Lists::class)]
#[UsesClass(IntruderAlert\Helper\Json::class)]
#[UsesClass(IntruderAlert\Helper\File::class)]
#[CoversClass(IntruderAlert\Cache::class)]
#[CoversClass(IntruderAlert\Database\Country::class)]
#[CoversClass(IntruderAlert\Database\Database::class)]
#[CoversClass(IntruderAlert\Database\Network::class)]
#[CoversClass(IntruderAlert\Database\Updater\Downloader::class)]
#[CoversClass(IntruderAlert\Database\Updater\Extractor::class)]
#[CoversClass(IntruderAlert\Database\Updater\Updater::class)]
#[CoversClass(IntruderAlert\Database\Updater\Url::class)]
#[CoversClass(IntruderAlert\Fetch::class)]
#[CoversClass(IntruderAlert\Helper\Output::class)]
#[CoversClass(IntruderAlert\Helper\Timer::class)]
#[CoversClass(IntruderAlert\Ip::class)]
#[CoversClass(IntruderAlert\List\AbstractList::class)]
#[CoversClass(IntruderAlert\List\Addresses::class)]
#[CoversClass(IntruderAlert\List\Continents::class)]
#[CoversClass(IntruderAlert\List\Countries::class)]
#[CoversClass(IntruderAlert\List\Dates::class)]
#[CoversClass(IntruderAlert\List\Jails::class)]
#[CoversClass(IntruderAlert\List\Networks::class)]
#[CoversClass(IntruderAlert\List\Subnets::class)]
#[CoversClass(IntruderAlert\Logger::class)]
#[CoversClass(IntruderAlert\Logs\LineExtractor::class)]
#[CoversClass(IntruderAlert\Logs\Logs::class)]
#[CoversClass(IntruderAlert\Report::class)]
class BackendTest extends AbstractTestCase
{
    private static string $cacheFile;
    private static string $dataFile;

    public static function setupBeforeClass(): void
    {
        mockfs::create();

        self::$cacheFile = mockfs::getUrl('/cache.json');
        self::$dataFile = mockfs::getUrl('/data.json');
    }

    public function setup(): void
    {
        mockfs::create();
    }

    public function testRun(): void
    {
        $this->expectOutputRegex('/Created report JSON file/');

        /** @var Config&\PHPUnit\Framework\MockObject\Stub */
        $config = $this->createConfigStub();
        $config->method('getLogPaths')->willReturn('./backend/tests/files/logs/has-bans/fail2ban.log');

        $backend = new Backend($config);
        $backend->run();

        $this->assertFileExists(self::$cacheFile);
        $this->assertFileExists(self::$dataFile);
    }

    public function testRunWithCache(): void
    {
        $this->expectOutputRegex('/Created report JSON file/');

        $date = new DateTime();
        $cache = [
            'expires' => $date->modify('+3 hour')->format('U'),
            'items' => [
                '81.2.69.144' => [
                    'address' => '81.2.69.144',
                    'version' => 4,
                    'network' => [
                        'name' => 'Aaisp',
                        'number' => 20712,
                        'subnet' => '81.2.64.0/18'
                    ],
                    'country' => [
                        'name' => 'United Kingdom',
                        'code' => 'GB'
                    ],
                    'continent' => [
                        'name' => 'Europe',
                        'code' => 'EU'
                    ]
                ]
            ]
        ];

        file_put_contents(self::$cacheFile, json_encode($cache));

        /** @var Config&\PHPUnit\Framework\MockObject\Stub */
        $config = $this->createConfigStub();
        $config->method('getLogPaths')->willReturn('./backend/tests/files/logs/has-bans/fail2ban.log');

        $backend = new Backend($config);
        $backend->run();

        $this->assertFileExists(self::$cacheFile);
        $this->assertFileExists(self::$dataFile);
    }

    public function testGenerateErrorReport(): void
    {
        $this->expectOutputRegex('/Scanned 1 lines and found 0 bans/');

        /** @var Config&\PHPUnit\Framework\MockObject\Stub */
        $config = $this->createConfigStub();
        $config->method('getLogPaths')->willReturn('./backend/tests/files/logs/no-bans/fail2ban.log');

        $backend = new Backend($config);
        $backend->run();

        $this->assertFileExists(self::$dataFile);

        $actual = json_decode((string) file_get_contents(self::$dataFile), associative: true);

        $this->assertArrayHasKey('error', $actual);
        $this->assertArrayHasKey('message', $actual);
        $this->assertTrue($actual['error']);
        $this->assertEquals('No bans found', $actual['message']);
    }

    /**
     * Create config stub
     */
    private function createConfigStub(): Config
    {
        $config = $this->createStub(Config::class);
        $config->method('getAsnDatabasePath')->willReturn('./backend/tests/files/mmdb/GeoLite2-ASN-Test.mmdb');
        $config->method('getCountryDatabasePath')->willReturn('./backend/tests/files/mmdb/GeoLite2-Country-Test.mmdb');
        $config->method('getCacheFilePath')->willReturn(self::$cacheFile);
        $config->method('getDataFilePath')->willReturn(self::$dataFile);
        $config->method('getTimezone')->willReturn('Europe/London');
        $config->method('getSystemLogTimezone')->willReturn('UTC');

        return $config;
    }
}

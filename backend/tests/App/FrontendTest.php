<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use IntruderAlert\App\Frontend;
use IntruderAlert\Config;

#[CoversClass(Frontend::class)]
#[UsesClass(Config::class)]
#[UsesClass(IntruderAlert\App\AbstractApp::class)]
#[UsesClass(IntruderAlert\Logger::class)]
#[UsesClass(IntruderAlert\Lists::class)]
#[UsesClass(IntruderAlert\Helper\Json::class)]
#[UsesClass(IntruderAlert\Helper\File::class)]
class FrontendTest extends AbstractTestCase
{
    public function tearDown(): void
    {
        $_POST['hash'] = '';
    }

    /**
     * Test `getJsonReport()`
     */
    public function testGetJsonReport(): void
    {
        $expectedSettings = [
            'features' => [
                'charts' => true,
                'updates' => true,
                'daemonLog' => true
            ],
            'defaults' => [
                'chart' => 'last24hours',
                'pageSize' => 50
            ],
            'timezone' => 'Europe/London',
            'version' => 'v0.0.0'
        ];

        /** @var Config&\PHPUnit\Framework\MockObject\Stub */
        $config = $this->createConfigStub();
        $config->method('getDashDaemonLogStatus')->willReturn(true);
        $config->method('getDashDefaultChart')->willReturn('last24hours');
        $config->method('getDashPageSize')->willReturn(50);
        $config->method('getDataFilePath')->willReturn('backend/tests/files/expected-report.json');

        $app = new Frontend($config);
        $json = $app->getJsonReport();
        $actual = json_decode($app->getJsonReport(), associative: true);

        $this->assertJson($json);
        $this->assertEquals($expectedSettings, $actual['settings']);
    }

    /**
     * Test `getJsonReport()` with dashboard daemon log disabled
     */
    public function testGetJsonReportNoDaemonLog(): void
    {
        /** @var Config&\PHPUnit\Framework\MockObject\Stub */
        $config = $this->createConfigStub();
        $config->method('getDashDaemonLogStatus')->willReturn(false);
        $config->method('getDataFilePath')->willReturn('backend/tests/files/expected-report.json');

        $app = new Frontend($config);
        $actual = json_decode($app->getJsonReport(), associative: true);

        $this->assertArrayNotHasKey('log', $actual);
        $this->assertFalse($actual['settings']['features']['daemonLog']);
    }

    /**
     * Test `getJsonReport()` with `$_POST['hash']` set to an old hash.
     */
    public function testGetJsonReportWithOldHashPassed(): void
    {
        /** @var Config&\PHPUnit\Framework\MockObject\Stub */
        $config = $this->createConfigStub();
        $config->method('getDashDaemonLogStatus')->willReturn(false);
        $config->method('getDataFilePath')->willReturn('backend/tests/files/expected-report.json');

        $_POST['hash'] = 'qwerty';

        $app = new Frontend($config);
        $actual = json_decode($app->getJsonReport(), associative: true);

        $this->assertTrue($actual['hasUpdates']);
    }

    /**
     * Test `getJsonReport()` with `$_POST['hash']` set to the current hash.
     */
    public function testGetJsonReportWithCurrentHashPassed(): void
    {
        /** @var Config&\PHPUnit\Framework\MockObject\Stub */
        $config = $this->createConfigStub();
        $config->method('getDashDaemonLogStatus')->willReturn(false);
        $config->method('getDataFilePath')->willReturn('backend/tests/files/expected-report.json');

        $_POST['hash'] = '7ab39063e802dca401ea7d40190301f3a0338f70';

        $app = new Frontend($config);
        $actual = json_decode($app->getJsonReport(), associative: true);

        $this->assertEmpty($actual);
    }

    /**
     * Test `getJsonReport()` with no data file
     */
    public function testGetJsonReportNoDataFile(): void
    {
        $expected = (string) json_encode([
            'error' => true,
            'message' => 'No data. Is the backend script setup?'
        ]);

        /** @var Config&\PHPUnit\Framework\MockObject\Stub */
        $config = $this->createConfigStub();
        $config->method('getDataFilePath')->willReturn('no-data-file.json');

        $app = new Frontend($config);

        $this->assertJsonStringEqualsJsonString($expected, $app->getJsonReport());
    }

    /**
     * Create config stub
     */
    private function createConfigStub(): Config
    {
        $config = $this->createStub(Config::class);
        $config->method('getChartsStatus')->willReturn(true);
        $config->method('getDashUpdatesStatus')->willReturn(true);
        $config->method('getTimezone')->willReturn('Europe/London');
        $config->method('getVersion')->willReturn('v0.0.0');

        return $config;
    }
}

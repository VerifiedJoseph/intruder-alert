<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\App\Frontend;
use IntruderAlert\Config;

class FrontendTest extends TestCase
{
    /**
     * Test `getJsonReport()`
     */
    public function testGetJsonReport(): void
    {
        $config = $this->createConfigStub();
        $app = new Frontend($config);
    }

    public function testGetJsonReportNoDataFile(): void
    {
        $expected = json_encode([
            'error' => true,
            'message' => 'No data. Is the backend script setup?'
        ]);

        /** @var Config&\PHPUnit\Framework\MockObject\Stub */
        $config = $this->createConfigStub();
        $config->method('getPath')->willReturn('./backend/tests/files/no-data-file.json');

        $app = new Frontend($config);

        $this->assertJsonStringEqualsJsonString($expected, $app->getJsonReport());
    }

    /**
     * Create config stub
     */
    private function createConfigStub(): Config
    {
        $config = $this->createStub(Config::class);
        $config->method('getDashDaemonLogStatus')->willReturn(true);
        $config->method('getChartsStatus')->willReturn(true);
        $config->method('getDashUpdatesStatus')->willReturn(true);
        $config->method('getDashDaemonLogStatus')->willReturn(true);
        $config->method('getTimezone')->willReturn('Europe/London');
        $config->method('getVersion')->willReturn('v0.0.0');

        return $config;
    }
}

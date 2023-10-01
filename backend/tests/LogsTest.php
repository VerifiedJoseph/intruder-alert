<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Logs;
use IntruderAlert\Config;
use IntruderAlert\Helper\Output;
use IntruderAlert\Exception\AppException;
use IntruderAlert\Exception\ReportException;

class LogsTest extends TestCase
{
    /** @var array<int, array<string, string>> $lines Test log lines */
    private array $lines = [];

    public function setUp(): void
    {
        $data = (string) file_get_contents('./backend/tests/files/log-lines.json');
        $this->lines = json_decode($data, associative: true);

        Output::quiet();
    }

    /**
     * @return Config&PHPUnit\Framework\MockObject\Stub
     */
    private function createConfigStub(): Config
    {
        $config = $this->createStub(Config::class);
        $config->method('getTimezone')->willReturn('UTC');
        $config->method('getSystemLogTimezone')->willReturn('UTC');

        return $config;
    }

    /**
     * Test class with the logs folder
     */
    public function testLogsClassWithLogsFolder(): void
    {
        $config = $this->createConfigStub();
        $config->method('getLogFolder')->willReturn('./backend/tests/files/logs/has-bans');

        $logs = new Logs($config);
        $rows = $logs->process();

        $this->assertCount(2, $rows);

        foreach ($rows as $index => $row) {
            $this->assertEquals($this->lines[$index]['ip'], $row['ip']);
            $this->assertEquals($this->lines[$index]['jail'], $row['jail']);
            $this->assertEquals($this->lines[$index]['timestamp'], $row['timestamp']);
        }
    }

    /**
     * Test class with a log file that contains two bans
     */
    public function testLogsClassWithHasBansFiles(): void
    {
        $config = $this->createConfigStub();
        $config->method('getLogPaths')->willReturn('./backend/tests/files/logs/has-bans/fail2ban.log');

        $logs = new Logs($config);
        $rows = $logs->process();

        $this->assertCount(2, $rows);

        foreach ($rows as $index => $row) {
            $this->assertEquals($this->lines[$index]['ip'], $row['ip']);
            $this->assertEquals($this->lines[$index]['jail'], $row['jail']);
            $this->assertEquals($this->lines[$index]['timestamp'], $row['timestamp']);
        }
    }

    /**
     * Test class with a log file that contains no bans
     */
    public function testLogsClassWithNoBansFile(): void
    {
        $config = $this->createConfigStub();
        $config->method('getLogPaths')->willReturn('./backend/tests/files/logs/no-bans/fail2ban.log');

        $this->expectException(ReportException::class);
        $this->expectExceptionMessage('No bans found');

        $logs = new Logs($config);
        $logs->process();
    }

    /**
     * Test 'Failed to read file' AppException
     */
    public function testLogsClassWithMissingLogFile(): void
    {
        $config = $this->createConfigStub();
        $config->method('getLogPaths')->willReturn('./no-found.log');

        // $this->expectOutputString("[intruder-alert] Processing ./no-found.log \n");
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('App error: Failed to read file');

        $logs = new Logs($config);
        $logs->process();
    }
}

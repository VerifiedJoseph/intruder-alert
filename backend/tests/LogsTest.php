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
    private array $lines = [
        [
            'ip' => '127.0.0.1',
            'jail' => 'sshd',
            'timestamp' => '2023-02-05 00:06:57'
        ],
        [
            'ip' => '2001:67c:930::1',
            'jail' => 'nginx',
            'timestamp' => '2023-02-05 00:06:57'
        ]
    ];

    private function createConfigStub(): \IntruderAlert\Config
    {
        $config = $this->createStub(Config::class);
        $config->method('getTimezone')->willReturn('UTC');
        $config->method('getSystemLogTimezone')->willReturn('UTC');

        return $config;
    }

    public function setUp(): void
    {
        Output::quiet();
    }

    /**
     * Test class with the logs folder
     */
    public function testLogsClassWithLogsFolder(): void
    {
        $config = $this->createConfigStub();
        $config->method('getLogFolder')->willReturn('./backend/tests/files/logs');

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

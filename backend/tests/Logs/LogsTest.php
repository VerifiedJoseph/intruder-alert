<?php

use PHPUnit\Framework\TestCase;
use MockFileSystem\MockFileSystem as mockfs;
use IntruderAlert\Config;
use IntruderAlert\Logger;
use IntruderAlert\Logs\Logs;
use IntruderAlert\Exception\AppException;
use IntruderAlert\Exception\LogsException;

class LogsTest extends TestCase
{
    /** @var array<int, array<string, string>> $lines Test log lines */
    private array $lines = [];

    public function setUp(): void
    {
        $data = (string) file_get_contents('./backend/tests/files/log-lines.json');
        $this->lines = json_decode($data, associative: true);
    }

    public function tearDown(): void
    {
        stream_context_set_default(
            [
                'mfs' => [
                    'fopen_fail' => false,
                ]
            ]
        );
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
        $this->expectOutputRegex('/Found 2 bans in all files/');

        $config = $this->createConfigStub();
        $config->method('getLogFolder')->willReturn('./backend/tests/files/logs/has-bans');

        $logs = new Logs($config, new Logger());
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
        $this->expectOutputRegex('/Found 2 bans in all files/');

        $config = $this->createConfigStub();
        $config->method('getLogPaths')->willReturn('./backend/tests/files/logs/has-bans/fail2ban.log');

        $logs = new Logs($config, new Logger());
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

        $this->expectOutputRegex('/Scanned 1 lines and found 0 bans/');
        $this->expectException(LogsException::class);
        $this->expectExceptionMessage('No bans found');

        $logs = new Logs($config, new Logger());
        $logs->process();
    }

    /**
     * Test 'Failed to read file' AppException
     */
    public function testLogsClassWithMissingLogFile(): void
    {
        $config = $this->createConfigStub();
        $config->method('getLogPaths')->willReturn('./no-found.log');

        $this->expectOutputRegex('/Processing .\/no-found.log/');
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('App error: Failed to read file');

        $logs = new Logs($config, new Logger());
        $logs->process();
    }

    /**
     * Test `process()` with empty log file
     */
    public function testProcessWithEmptyLogFile(): void
    {
        mockfs::create();
        $file = mockfs::getUrl('/empty.log');
        touch($file);

        $logs = sprintf('%s,./backend/tests/files/logs/has-bans/fail2ban.log', $file);
        $config = $this->createConfigStub();
        $config->method('getLogPaths')->willReturn($logs);

        $this->expectOutputRegex('/File is empty. Skipping/');

        $logs = new Logs($config, new Logger());
        $logs->process();
    }

    /**
     * Test 'Failed to open file' exception in `process()`
     */
    public function testProcessFailedToOpen(): void
    {
        mockfs::create();
        $file = mockfs::getUrl('/file.log');
        file_put_contents($file, 'hello');

        $config = $this->createConfigStub();
        $config->method('getLogPaths')->willReturn($file);

        stream_context_set_default(
            [
                'mfs' => [
                    'fopen_fail' => true,
                ]
            ]
        );

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('App error: Failed to open file: ' . $file);
        $this->expectOutputRegex('/Processing/');

        $logs = new Logs($config, new Logger());
        $logs->process();
    }
}

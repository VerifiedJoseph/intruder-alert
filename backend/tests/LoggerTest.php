<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use IntruderAlert\Logger;
use IntruderAlert\Helper\Output;

#[CoversClass(Logger::class)]
#[UsesClass(Output::class)]
class LoggerTest extends AbstractTestCase
{
    /**
     * Test `getEntries()`
     */
    public function testGetEntries(): void
    {
        $this->expectOutputRegex('[intruder-alert]');

        $logger = new Logger('UTC', 1);
        $logger->info('Hello');
        $logger->info('World');

        $expected = ['Hello', 'World'];
        $this->assertEquals($expected, $logger->getEntries());
    }

    /**
     * Test `info()`
     */
    public function testInfo(): void
    {
        $this->expectOutputString(sprintf(
            '[intruder-alert] %s %s',
            'Hello',
            PHP_EOL
        ));

        $logger = new Logger('UTC', 1);
        $logger->info('Hello');
    }

    /**
     * Test `debug()`
     */
    public function testDebug(): void
    {
        $this->expectOutputRegex('/Hello/');

        $logger = new Logger('UTC', 2);
        $logger->debug('Hello');
    }

    /**
     * Test setting log level too low
     */
    public function testLogLevelTooLow(): void
    {
        $logger = new Logger('UTC', -1);

        $reflection = new \ReflectionClass($logger);
        $actual = $reflection->getProperty('logLevel')->getValue($logger);

        $this->assertEquals(1, $actual);
    }

    /**
     * Test setting log level too hight
     */
    public function testLogLevelTooHigh(): void
    {
        $logger = new Logger('UTC', 3);

        $reflection = new \ReflectionClass($logger);
        $actual = $reflection->getProperty('logLevel')->getValue($logger);

        $this->assertEquals(2, $actual);
    }
}

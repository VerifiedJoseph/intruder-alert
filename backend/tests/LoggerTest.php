<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Logger;
use IntruderAlert\Helper\Output;

class LoggerTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        Output::disableQuiet();
    }

    /**
     * Test `addEntry()`
     */
    public function testAddEntry(): void
    {
        $this->expectOutputRegex('/Hello World/');

        $logger = new Logger();
        $logger->addEntry('Hello World');
        $this->assertEquals(['Hello World'], $logger->getEntries());
    }

    /**
     * Test `addEntry()` with display boolean false
     */
    public function testAddEntryDisplayBooleanFalse(): void
    {
        $this->expectOutputString('');

        $logger = new Logger();
        $logger->addEntry('Hello World', display: false);
        $this->assertEquals(['Hello World'], $logger->getEntries());
    }
}

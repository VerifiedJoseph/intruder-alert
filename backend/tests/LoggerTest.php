<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Logger;

class LoggerTest extends TestCase
{
    /**
     * Test `addEntry()`
     */
    public function testAddEntry(): void
    {
        $this->expectOutputString('Hello World');

        $logger = new Logger();

        $logger->addEntry('Hello World');
        $this->assertEquals(['Hello World'], $logger->getEntries());
    }

    /**
     * Test `addEntry()` with display boolean as false
     */
    public function testAddEntryDisplayBooleanFalse(): void
    {
        $this->expectOutputString('');

        $logger = new Logger();

        $logger->addEntry('Hello World', display: false);
        $this->assertEquals(['Hello World'], $logger->getEntries());
    }
}

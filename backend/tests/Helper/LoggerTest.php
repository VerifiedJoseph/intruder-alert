<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Helper\Logger;

class LoggerTest extends TestCase
{
    public function setUp(): void
    {
        Logger::removeEntries();
    }

    /**
     * Test adding an entry
     */
    public function testAddEntry(): void
    {
        Logger::addEntry('Hello World');
        $this->assertEquals(['Hello World'], Logger::getEntries());
    }

    /**
     * Test removing all entries
     */
    public function testRemoveEntries(): void
    {
        Logger::addEntry('Hello World');
        $this->assertEquals(['Hello World'], Logger::getEntries());

        Logger::removeEntries();
        $this->assertEquals([], Logger::getEntries());
    }
}

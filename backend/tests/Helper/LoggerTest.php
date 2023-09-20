<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Helper\Logger;

class LoggerTest extends TestCase
{
    /**
     * Test adding an entry
     */
    public function testAddEntry(): void
    {
        $entries = ['Hello World'];

        Logger::addEntry('Hello World');

        $this->assertEquals($entries, Logger::getEntries());
    }
}

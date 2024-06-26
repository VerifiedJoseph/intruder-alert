<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use IntruderAlert\Logger;
use IntruderAlert\Helper\Output;

#[CoversClass(Logger::class)]
#[UsesClass(Output::class)]
class LoggerTest extends AbstractTestCase
{
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

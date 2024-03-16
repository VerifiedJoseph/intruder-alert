<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Helper\Output;

class OutputTest extends TestCase
{
    /**
     * @var string $text
     */
    private string $text = 'Hello World';

    /**
     * @var string $outputText
     */
    private $outputText = "[intruder-alert] Hello World \n";

    /**
     * Test output()
     */
    public function testOutput(): void
    {
        $this->expectOutputString($this->outputText);

        Output::text($this->text);
    }

    /**
     * Test newline()
     */
    public function testNewline(): void
    {
        $this->expectOutputString(PHP_EOL);

        Output::newline();
    }
}

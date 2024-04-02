<?php

use PHPUnit\Framework\Attributes\CoversClass;
use IntruderAlert\Helper\Output;

#[CoversClass(Output::class)]
class OutputTest extends AbstractTestCase
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
    public function testText(): void
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

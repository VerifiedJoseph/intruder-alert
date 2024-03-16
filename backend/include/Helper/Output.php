<?php

namespace IntruderAlert\Helper;

final class Output
{
    /**
     * Display text in terminal
     *
     * @param string $text Text string to display
     */
    public static function text(string $text = ''): void
    {
        echo sprintf("[intruder-alert] %s \n", $text);
    }

    /**
     * Output system newline character in terminal
     */
    public static function newline(): void
    {
        echo PHP_EOL;
    }
}

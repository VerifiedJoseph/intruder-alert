<?php

namespace Helper;

final class Output
{
    /**
     * @var bool $quiet Suppress output status
     */
    private static bool $quiet = false;

    /**
     * Suppress output
     */
    public static function quiet(): void
    {
        self::$quiet = true;
    }

    /**
     * Disable suppressing output
     */
    public static function disableQuiet(): void
    {
        self::$quiet = false;
    }

    /**
     * Display text in terminal
     *
     * @param string $text Text string to display
     */
    public static function text(string $text = ''): void
    {
        if (self::$quiet === false) {
            echo $text . "\n";
        }
    }

    /**
     * Output system newline character in terminal
     */
    public static function newline(): void
    {
        if (self::$quiet === false) {
            echo PHP_EOL;
        }
    }
}

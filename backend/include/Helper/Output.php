<?php

namespace IntruderAlert\Helper;

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
     * @param bool $log Add message to logger
     */
    public static function text(string $text = '', bool $log = false): void
    {
        if (self::$quiet === false) {
            echo sprintf("[intruder-alert] %s \n",  $text);
        }

        if ($log === true) {
            Logger::addEntry($text);
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

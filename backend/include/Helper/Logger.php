<?php

namespace IntruderAlert\Helper;

class Logger
{
    /** @var array<int, string> $entries */
    private static array $entries = [];

    /**
     * Add entry
     *
     * @param string $message Entry message
     */
    public static function addEntry(string $message): void
    {
        self::$entries[] = $message;
    }

    /**
     * Get Entries
     *
     * @return array<int, string>
     */
    public static function getEntries(): array
    {
        return self::$entries;
    }
}

<?php

namespace IntruderAlert;

use IntruderAlert\Helper\Output;

class Logger
{
    /** @var array<int, string> $entries */
    private array $entries = [];

    /**
     * Add entry
     *
     * @param string $message Entry message
     * @param bool $display Toggle displaying the message
     */
    public function addEntry(string $message, bool $display = true): void
    {
        if ($display === true) {
            Output::text($message);
        }

        $this->entries[] = $message;
    }

    /**
     * Get Entries
     *
     * @return array<int, string>
     */
    public function getEntries(): array
    {
        return $this->entries;
    }
}

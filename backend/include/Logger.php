<?php

namespace IntruderAlert;

use DateTime;
use DateTimeZone;
use IntruderAlert\Helper\Output;

class Logger
{
    private DateTimeZone $timezone;

    /** @var array<int, array<string, mixed>> $entries Logger entries  */
    private array $entries = [];

    /**
     * Logging level
     *
     * - `1` - Normal
     * - `2` - Debug
     *
     * @var int
     */
    private int $logLevel = 1;

    /**
     *
     * @param string $timezone Timezone
     * @param int $level Logging level (`1` - Normal, `2` - Debug)
     */
    public function __construct(string $timezone, int $level = 1)
    {
        $this->setLevel($level);
        $this->timezone = new DateTimeZone($timezone);
    }

    /**
     * Return info level entries
     *
     * @return array<int, string>
     */
    public function getEntries(): array
    {
        $data = [];

        foreach ($this->entries as $entry) {
            if ($entry['level'] === 1) {
                $data[] = $entry['message'];
            }
        }

        return $data;
    }

    /**
     * Add info message
     *
     * @param string $message Message text
     */
    public function info(string $message): void
    {
        $this->log($message, 1);
    }

    /**
     * Add debug message
     *
     * @param string $message Message text
     */
    public function debug(string $message): void
    {
        if ($this->logLevel === 2) {
            $this->log($message, 2);
        }
    }

    /**
     * Add message to logger and display in terminal
     *
     * @param string $message Message text
     * @param int $level Message log level
     */
    private function log(string $message, int $level): void
    {
        Output::text($message);

        $date = new DateTime('now', $this->timezone);

        $this->entries[] = [
            'timestamp' => $date->format('Y-m-d H:i:s P'),
            'message' => $message,
            'level' => $level
        ];
    }

    /**
     * Set logging level
     *
     * - `1` - Normal
     * - `2` - Verbose
     *
     * @param int $level Logging level
     */
    private function setLevel(int $level): void
    {
        if ($level < 1) {
            $this->logLevel = 1;
        } elseif ($level > 2) {
            $this->logLevel = 2;
        } else {
            $this->logLevel = $level;
        }
    }
}

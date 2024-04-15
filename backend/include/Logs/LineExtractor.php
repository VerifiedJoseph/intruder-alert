<?php

namespace IntruderAlert\Logs;

/**
 * Class for extracting Fail2ban ban details from a log file line
 */
class LineExtractor
{
    /** @var string $regex Regex for finding bans in a log ine */
    private $regex = <<<REGEX
     /(?<timestamp>[0-9]{4}-[0-9]{2}-[0-9]{2}\ [0-9]{2}:[0-9]{2}:[0-9]{2})
     ,[0-9]+\ fail2ban*.+
     \[(?<jail>[^.]+)]\ 
     Ban\ (?<ip>[0-9a-z.:]+)
     /ix
    REGEX;

    /** @var boolean $matched Regex match status */
    private bool $matched = false;

    /** @var string $ip IP address */
    private string $ip = '';

    /** @var string $jail Jail */
    private string $jail = '';

    /** @var string $timestamp Timestamp */
    private string $timestamp = '';

    public function __construct(string $line)
    {
        $this->regex($line);
    }

    /**
     * Get IP address found by the regex
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * Get jail found by the regex
     */
    public function getJail(): string
    {
        return $this->jail;
    }

    /**
     * Get timestamp found by the regex
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * Returns boolean indicating regex found ban
     */
    public function hasBan(): bool
    {
        return $this->matched;
    }

    /**
     * Run regex on log line
     */
    private function regex(string $line): void
    {
        preg_match($this->regex, $line, $match);

        if ($match !== []) {
            $this->matched = true;
            $this->ip = $match['ip'];
            $this->jail = $match['jail'];
            $this->timestamp = $match['timestamp'];
        }
    }
}

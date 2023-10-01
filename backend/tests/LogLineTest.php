<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\LogLine;

class LogLineTest extends TestCase
{
    /** @var array<string, object> $lines Test log lines */
    private array $lines = [];

    public function setUp(): void
    {
        $this->lines = json_decode(
            file_get_contents('./backend/tests/files/log-lines.json')
        );
    }

    /**
     * Test LogLine class with a IPv4 ban
     */
    public function testIpv4Ban(): void
    {
        $line = new LogLine($this->lines[0]->line);

        $this->assertTrue($line->hasBan());
        $this->assertEquals($this->lines[0]->ip, $line->getIp());
        $this->assertEquals($this->lines[0]->jail, $line->getJail());
        $this->assertEquals($this->lines[0]->timestamp, $line->getTimestamp());
    }

    /**
     * Test LogLine class with a IPv6 ban
     */
    public function testIpv6Ban(): void
    {
        $line = new LogLine($this->lines[1]->line);

        $this->assertTrue($line->hasBan());
        $this->assertEquals($this->lines[1]->ip, $line->getIp());
        $this->assertEquals($this->lines[1]->jail, $line->getJail());
        $this->assertEquals($this->lines[1]->timestamp, $line->getTimestamp());
    }

    /**
     * Test LogLine class with a line does not contain a ban
     */
    public function testNoBanLine(): void
    {
        $line = new LogLine($this->lines[2]->line);

        $this->assertFalse($line->hasBan());
    }
}

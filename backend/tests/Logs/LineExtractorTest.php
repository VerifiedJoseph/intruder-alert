<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use IntruderAlert\Logs\LineExtractor;

#[CoversClass(LineExtractor::class)]
class LineExtractorTest extends AbstractTestCase
{
    /** @var array<int, array<string, string>> $lines Test log lines */
    private array $lines = [];

    public function setUp(): void
    {
        $data = (string) file_get_contents('./backend/tests/files/log-lines.json');
        $this->lines = json_decode($data, associative: true);
    }

    /**
     * Test LogLine class with a IPv4 ban
     */
    public function testIpv4Ban(): void
    {
        $line = new LineExtractor($this->lines[0]['line']);

        $this->assertTrue($line->hasBan());
        $this->assertEquals($this->lines[0]['ip'], $line->getIp());
        $this->assertEquals($this->lines[0]['jail'], $line->getJail());
        $this->assertEquals($this->lines[0]['timestamp'], $line->getTimestamp());
    }

    /**
     * Test LogLine class with a IPv6 ban
     */
    public function testIpv6Ban(): void
    {
        $line = new LineExtractor($this->lines[1]['line']);

        $this->assertTrue($line->hasBan());
        $this->assertEquals($this->lines[1]['ip'], $line->getIp());
        $this->assertEquals($this->lines[1]['jail'], $line->getJail());
        $this->assertEquals($this->lines[1]['timestamp'], $line->getTimestamp());
    }

    /**
     * Test LogLine class with a line does not contain a ban
     */
    public function testNoBanLine(): void
    {
        $line = new LineExtractor($this->lines[2]['line']);

        $this->assertFalse($line->hasBan());
    }
}

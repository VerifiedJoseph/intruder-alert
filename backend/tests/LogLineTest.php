<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\LogLine;

class LogLineTest extends TestCase
{
	/** @var array<string, array<string, string>> $lines Test log lines */
	private array $lines = [
		'ipv4Ban' => [
			'line' => '2023-02-05 00:06:57,449 fail2ban.actions        [40619]: NOTICE  [sshd] Ban 127.0.0.1',
			'ip' => '127.0.0.1',
			'jail' => 'sshd',
			'timestamp' => '2023-02-05 00:06:57'
		],
		'ipv6Ban' => [
			'line' => '2023-02-05 00:06:57,449 fail2ban.actions        [40619]: NOTICE  [nginx] Ban 2001:67c:930::1',
			'ip' => '2001:67c:930::1',
			'jail' => 'nginx',
			'timestamp' => '2023-02-05 00:06:57'
		],
		'noBan' => [
			'line' => '2023-02-05 00:28:13,052 fail2ban.filter         [40619]: INFO    [sshd] Found 127.0.0.1 - 2023-02-05 00:28:13',
		]
	];

    /**
     * Test LogLine class with a IPv4 ban
     */
    public function testIpv4Ban(): void
    {
		$line = new LogLine($this->lines['ipv4Ban']['line']);
		
		$this->assertTrue($line->hasBan());
		$this->assertEquals($this->lines['ipv4Ban']['ip'], $line->getIp());
		$this->assertEquals($this->lines['ipv4Ban']['jail'], $line->getJail());
		$this->assertEquals($this->lines['ipv4Ban']['timestamp'], $line->getTimestamp());
    }

    /**
     * Test LogLine class with a IPv6 ban
     */
    public function testIpv6Ban(): void
    {
		$line = new LogLine($this->lines['ipv6Ban']['line']);
		
		$this->assertTrue($line->hasBan());
		$this->assertEquals($this->lines['ipv6Ban']['ip'], $line->getIp());
		$this->assertEquals($this->lines['ipv6Ban']['jail'], $line->getJail());
		$this->assertEquals($this->lines['ipv6Ban']['timestamp'], $line->getTimestamp());
    }

    /**
     * Test LogLine class with a line does not contain a ban
     */
    public function testNoBanLine(): void
    {
		$line = new LogLine($this->lines['noBan']['line']);
		
		$this->assertFalse($line->hasBan());
    }
}

<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Ip;

class IpTest extends TestCase
{
    private array $ipTemplate = [
        'address' => null,
        'version' => 0,
        'jail' => 'sshd',
        'timestamp' => '1970-01-01 00:00:00',
        'country' => [
            'name' => 'United Kingdom',
            'code' => 'GB',
        ],
        'continent' => [
            'name' => 'Europe',
            'code' => 'EU',
        ],
        'network' => [
            'name' => 'Host',
            'number' => 0,
            'subnet' => null
        ]
    ];

    /**
     * Test creating a new Ip class with an Ipv4 address
     */
    public function testIpClassWithIpv4(): void
    {
        $ipv4 = $this->ipTemplate;
        $ipv4['address'] = '127.0.0.1';
        $ipv4['version'] = 4;
        $ipv4['network']['subnet'] = '127.0.0.0/8';

        $ip = new Ip($ipv4['address']);
        $ip->setJail($ipv4['jail']);
        $ip->setTimestamp($ipv4['timestamp']);
        $ip->setCountry($ipv4['country']);
        $ip->setContinent($ipv4['continent']);
        $ip->setNetwork($ipv4['network']);

        $this->assertEquals($ipv4, $ip->getDetails());
    }

    /**
     * Test creating a new Ip class with an Ipv6 address
     */
    public function testIpClassWithIpv6(): void
    {
        $ipv6 = $this->ipTemplate;
        $ipv6['address'] = '2001:67c:930::1';
        $ipv6['version'] = 6;
        $ipv6['network']['subnet'] = '2001:67c:930::/48';

        $ip = new Ip($ipv6['address']);
        $ip->setJail($ipv6['jail']);
        $ip->setTimestamp($ipv6['timestamp']);
        $ip->setCountry($ipv6['country']);
        $ip->setContinent($ipv6['continent']);
        $ip->setNetwork($ipv6['network']);

        $this->assertEquals($ipv6, $ip->getDetails());
    }
}

<?php

use Helper\Misc;

class Ip 
{
    /** @var array<string, mixed> $data IP address details */
    private array $data = [];

    /**
     * @param string $address IP address
     * @param string $jail Jail name
     * @param string $timestamp Event timestamp
     */
    public function __construct(string $address, string $jail, string $timestamp)
    {
        $this->data['address'] = $address;
        $this->data['version'] = Misc::detectIpVersion($address);
        $this->data['jail'] = $jail;
        $this->data['timestamp'] = $timestamp;
        $this->data['network'] = Lookup::network($address);
        $this->data = array_merge($this->data, Lookup::country($address));
    }

    /**
     * Get IP address details
     * 
     * @return array<string, mixed>
     */
    public function getDetails(): array
    {
        return $this->data;
    }
}
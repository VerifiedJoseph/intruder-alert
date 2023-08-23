<?php

class Ip
{
    /** @var array<string, mixed> $data IP address details */
    private array $data = [];

    /**
     * @param string $address IP address
     */
    public function __construct(string $address)
    {
        $this->data['address'] = $address;
        $this->data['version'] = $this->detectIpVersion($address);
    }

    public function setJail(string $jail): void
    {
        $this->data['jail'] = $jail;
    }

    public function setTimestamp(string $timestamp): void
    {
        $this->data['timestamp'] = $timestamp;
    }

    public function setNetwork(array $network): void
    {
        $this->data['network'] = $network;
    }

    public function setCountry(array $country): void
    {
        $this->data = array_merge($this->data, $country);
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

    /**
     * Detect internet protocol address version
     * 
     * @param string $address IP address
     * @return int Address version
     */
    private function detectIpVersion(string $address): int
    {
        if (str_contains($address, ':') === true) {
            return 6;
        }

        return 4;
    }
}
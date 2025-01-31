<?php

declare(strict_types=1);

namespace IntruderAlert;

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

    /**
     * Set Fail2ban jail
     *
     * @param string $jail
     */
    public function setJail(string $jail): void
    {
        $this->data['jail'] = $jail;
    }

    /**
     * Set ban event timestamp
     *
     * @param string $timestamp
     */
    public function setTimestamp(string $timestamp): void
    {
        $this->data['timestamp'] = $timestamp;
    }

    /**
     * Set country
     *
     * @param array<string, string> $country
     */
    public function setCountry(array $country): void
    {
        $this->data['country'] = $country;
    }

    /**
     * Set continent
     *
     * @param array<string, string> $continent
     */
    public function setContinent(array $continent): void
    {
        $this->data['continent'] = $continent;
    }

    /**
     * Set network
     *
     * @param array<string, string|int> $network
     */
    public function setNetwork(array $network): void
    {
        $this->data['network'] = $network;
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

<?php

declare(strict_types=1);

namespace IntruderAlert;

use IntruderAlert\List;

class Lists
{
    private List\Addresses $addresses;
    private List\Dates $dates;
    private List\Jails $jails;
    private List\Networks $networks;
    private List\Subnets $subnets;
    private List\Countries $countries;
    private List\Continents $continents;

    public function __construct()
    {
        $this->addresses = new List\Addresses();
        $this->dates = new List\Dates();
        $this->jails = new List\Jails();
        $this->networks = new List\Networks();
        $this->subnets = new List\Subnets();
        $this->countries = new List\Countries();
        $this->continents = new List\Continents();
    }

    /**
     * Add IP address
     *
     * @param array<string, mixed> $ip IP address details
     */
    public function addIp(array $ip): void
    {
        $this->addresses->addIp($ip);
        $this->dates->addIp($ip);
        $this->jails->addIp($ip);
        $this->networks->addIp($ip);
        $this->subnets->addIp($ip);
        $this->countries->addIp($ip);
        $this->continents->addIp($ip);
    }

    /**
     * Get lists
     *
     * @return array<string, mixed>
     */
    public function get(): array
    {
        return [
            'address' => $this->addresses->get(),
            'date' => $this->dates->get(),
            'jail' => $this->jails->get(),
            'network' => $this->networks->get(),
            'subnet' => $this->subnets->get(),
            'country' => $this->countries->get(),
            'continent' => $this->continents->get()
        ];
    }

    /**
     * Get item count for lists
     *
     * @return array<string, int>
     */
    public function getCounts(): array
    {
        return [
            'totalBans' => $this->addresses->getTotalBans(),
            'address' => $this->addresses->getCount(),
            'date' => $this->dates->getCount(),
            'jail' => $this->jails->getCount(),
            'network' => $this->networks->getCount(),
            'subnet' => $this->subnets->getCount(),
            'country' => $this->countries->getCount(),
            'continent' => $this->continents->getCount()
        ];
    }
}

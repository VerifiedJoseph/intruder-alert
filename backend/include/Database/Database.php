<?php

namespace IntruderAlert\Database;

use GeoIp2\Database\Reader;

abstract class Database
{
    /** @var Reader $Reader GeoIP2 database reader */
    protected Reader $reader;

    /**
     * @param string $path GeoIP database path
     */
    public function __construct(string $path)
    {
        $this->reader = new Reader($path);
    }

    /**
     * Lookup details for an IP address
     *
     * @param string $address IP address
     * @return array<mixed>
     */
    abstract public function lookup(string $address): array;
}
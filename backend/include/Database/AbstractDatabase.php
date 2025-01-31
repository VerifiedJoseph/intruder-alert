<?php

declare(strict_types=1);

namespace IntruderAlert\Database;

use IntruderAlert\Logger;
use GeoIp2\Database\Reader;

abstract class AbstractDatabase
{
    /** @var Reader $reader GeoIP2 database reader */
    protected Reader $reader;

    /** @var Logger $logger */
    protected Logger $logger;

    /**
     * @param string $path GeoIP database path
     */
    public function __construct(string $path, Logger $logger)
    {
        $this->reader = new Reader($path);
        $this->logger = &$logger;
    }

    /**
     * Lookup details for an IP address
     *
     * @param string $address IP address
     * @return array<mixed>
     */
    abstract public function lookup(string $address): array;
}

<?php

namespace IntruderAlert\Database;

use GeoIp2\Exception\AddressNotFoundException;

/**
 * Class for looking up IP address details in ASN GeoIP2 database
 */
class Network extends AbstractDatabase
{
    /**
     * Lookup network details for an IP address
     *
     * @param string $address IP address
     * @return array<string, string|int>
     */
    public function lookup(string $address): array
    {
        $data = [
            'name' => 'Unknown',
            'number' => 'Unknown',
            'subnet' => 'Unknown'
        ];

        try {
            $record = $this->reader->asn($address);
            $data['name'] = (string) $record->autonomousSystemOrganization;
            $data['number'] = (int) $record->autonomousSystemNumber;
            $data['subnet'] = (string) $record->network;
        } catch (AddressNotFoundException) {
            $this->logger->info('Address not found in GeoIP2 ASN database: ' . $address);
        } finally {
            return $data;
        }
    }
}

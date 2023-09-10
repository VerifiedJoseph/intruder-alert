<?php

namespace IntruderAlert\Database;

use IntruderAlert\Helper\Output;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

/**
 * Class for looking up IP address details in GeoIP2 databases
 */
class Lookup
{
    /** @var Reader $countryReader GeoIP2 country database reader */
    private Reader $countryReader;

    /** @var Reader $networkReader GeoIP2 network database reader */
    private Reader $networkReader;

    /**
     * Set country database
     *
     * @param string $path Database path
     */
    public function setCountryDatabase(string $path): void
    {
        $this->countryReader = new Reader($path);
    }

    /**
     * Set network database
     *
     * @param string $path Database path
     */
    public function setNetworkDatabase(string $path): void
    {
        $this->networkReader = new Reader($path);
    }

    /**
     * Lookup country details for an IP address
     *
     * @param string $address IP address
     * @return array<string, array<string, string>>
     */
    public function country(string $address): array
    {
        $data = [
            'country' => ['name' => 'Unknown', 'code' => 'Unknown'],
            'continent'  => ['name' => 'Unknown', 'code' => 'Unknown']
        ];

        try {
            $record = $this->countryReader->country($address);
            $names = (array) $record->continent->names;

            if ((string) $record->country->name !== '') {
                $data['continent']['name'] = $names['en'];
                $data['country']['name'] = (string) $record->country->name;
                $data['country']['code'] = (string) $record->country->isoCode;
                $data['continent']['code'] = (string) $record->continent->code;
            }
        } catch (AddressNotFoundException) {
            Output::text('Address not found in GeoIP2 country database: ' . $address, log: true);
        } finally {
            return $data;
        }
    }

    /**
     * Lookup network details for an IP address
     *
     * @param string $address IP address
     * @return array<string, string|int>
     */
    public function network(string $address): array
    {
        $data = [
            'name' => 'Unknown',
            'number' => 'Unknown',
            'subnet' => 'Unknown'
        ];

        try {
            $record = $this->networkReader->asn($address);
            $data['name'] = (string) $record->autonomousSystemOrganization;
            $data['number'] = (int) $record->autonomousSystemNumber;
            $data['subnet'] = (string) $record->network;
        } catch (AddressNotFoundException) {
            Output::text('Address not found in GeoIP2 ASN database: ' . $address, log: true);
        } finally {
            return $data;
        }
    }
}

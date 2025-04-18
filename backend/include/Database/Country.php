<?php

declare(strict_types=1);

namespace IntruderAlert\Database;

use GeoIp2\Exception\AddressNotFoundException;

/**
 * Class for looking up IP address details in country GeoIP2 database
 */
class Country extends AbstractDatabase
{
    /**
     * Lookup country details for an IP address
     *
     * @param string $address IP address
     * @return array<string, array<string, string>>
     */
    public function lookup(string $address): array
    {
        $data = [
            'country' => ['name' => 'Unknown', 'code' => 'Unknown'],
            'continent'  => ['name' => 'Unknown', 'code' => 'Unknown']
        ];

        try {
            $record = $this->reader->country($address);
            $names = (array) $record->continent->names;

            if ((string) $record->country->name !== '') {
                $data['continent']['name'] = $names['en'];
                $data['country']['name'] = (string) $record->country->name;
                $data['country']['code'] = (string) $record->country->isoCode;
                $data['continent']['code'] = (string) $record->continent->code;
            }
        } catch (AddressNotFoundException) {
            $this->logger->info('Address not found in GeoIP2 country database: ' . $address);
        } finally {
            return $data;
        }
    }
}

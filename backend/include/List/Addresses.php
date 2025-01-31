<?php

declare(strict_types=1);

namespace IntruderAlert\List;

class Addresses extends AbstractList
{
    /** {@inheritDoc} */
    protected ?string $mostBannedParam = 'address';

    protected int $totalBans = 0;

    /** {@inheritDoc} */
    public function addIp(array $ip): void
    {
        $address = $ip['address'];

        if (array_key_exists($address, $this->data['list']) === false) {
            $this->totalBans++;
            $this->data['list'][$address] = [
                'address' => $ip['address'],
                'version' => $ip['version'],
                'bans' => 1,
                'country' =>  $ip['country']['code'],
                'continent' =>  $ip['continent']['code'],
                'network' => $ip['network']['number'],
                'subnet' => $ip['network']['subnet'],
                'events' => [[
                    'timestamp' => $ip['timestamp'],
                    'jail' => $ip['jail']
                ]]
            ];
        } else {
            $this->totalBans++;
            $this->data['list'][$address]['bans']++;
            $this->data['list'][$address]['events'][] = [
                'timestamp' => $ip['timestamp'],
                'jail' => $ip['jail']
            ];
        }
    }

    /**
     * Get total number of bans
     */
    public function getTotalBans(): int
    {
        return $this->totalBans;
    }
}

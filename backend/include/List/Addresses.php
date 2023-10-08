<?php

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

    /**
     * Order by date
     */
    protected function orderByDate(): void
    {
        $list = $this->data['list'];

        foreach ($list as $itemKey => $item) {
            usort($this->data['list'][$itemKey]['events'], function ($a1, $a2) {
                $v1 = strtotime($a1['timestamp']);
                $v2 = strtotime($a2['timestamp']);
                return $v2 - $v1;
            });

            $this->data['list'][$itemKey]['firstSeen'] = $this->data['list'][$itemKey]['events'][0]['timestamp'];
        }

        usort($this->data['list'], function ($a1, $a2) {
            $v1 = strtotime($a1['firstSeen']);
            $v2 = strtotime($a2['firstSeen']);
            return $v2 - $v1;
        });
    }
}

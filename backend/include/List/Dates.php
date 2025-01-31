<?php

declare(strict_types=1);

namespace IntruderAlert\List;

class Dates extends AbstractList
{
    /** {@inheritDoc} */
    protected ?string $mostBannedParam = 'date';

    /**
     * Add IP address
     *
     * @param array<string, mixed> $ip IP address details
     */
    public function addIp(array $ip): void
    {
        $date = date('Y-m-d', strtotime($ip['timestamp']));

        if (array_key_exists($date, $this->data['list']) === false) {
            $this->data['list'][$date] = [
                'date' => $date,
                'bans' => 1,
                'ipCount' => 1,
            ];

            $this->ipList[$date][] = $ip['address'];
        } else {
            $this->data['list'][$date]['bans']++;

            if (in_array($ip['address'], $this->ipList[$date]) === false) {
                $this->ipList[$date][] = $ip['address'];
                $this->data['list'][$date]['ipCount']++;
            }
        }
    }

    /**
     * Order list by date
     */
    protected function orderList(): void
    {
        usort($this->data['list'], function ($a1, $a2) {
            $v1 = strtotime($a1['date']);
            $v2 = strtotime($a2['date']);
            return $v2 - $v1;
        });
    }
}

<?php

namespace IntruderAlert\List;

class Dates extends AbstractList
{
    /** {@inheritDoc} */
    protected ?string $mostBannedParam = 'date';

    /** {@inheritDoc} */
    protected string $orderItemsBy = 'date';

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
}

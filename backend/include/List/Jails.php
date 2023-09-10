<?php

namespace IntruderAlert\List;

class Jails extends AbstractList
{
    /** {@inheritDoc} */
    protected array $data = [
        'mostBanned' => '',
        'list' => []
    ];

    /** {@inheritDoc} */
    protected ?string $mostBannedParam = 'name';

    /** {@inheritDoc} */
    public function addIp(array $ip): void
    {
        $key = array_search($ip['jail'], array_column($this->data['list'], 'name'));

        if ($key === false) {
            $this->data['list'][] = [
                'name' => $ip['jail'],
                'ipCount' => 1,
                'bans' => 1
            ];

            $this->ipList[][] = $ip['address'];
        } else {
            $this->data['list'][$key]['bans']++;

            if (in_array($ip['address'], $this->ipList[$key]) === false) {
                $this->ipList[$key][] = $ip['address'];
                $this->data['list'][$key]['ipCount']++;
            }
        }
    }
}
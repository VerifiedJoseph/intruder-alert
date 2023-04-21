<?php

namespace List;

class Networks extends AbstractList
{
    /** {@inheritDoc} */
    protected array $data = [
        'mostBanned' => '',
        'list' => []
    ];

    /** {@inheritDoc} */
    protected array $settings = [
        'calculateMostBanned' => true,
        'orderBy' => 'bans'
    ];

    /** {@inheritDoc} */
    public function addIp(array $ip): void
    {
        $key = array_search($ip['network']['number'], array_column($this->data['list'], 'number'));

        if ($key === false) {
            $this->data['list'][] = [
                'name' => $ip['network']['name'],
                'number' => $ip['network']['number'],
                'bans' => 1,
                'ipCount' => 1,
            ];

            $this->ipList[] = $ip['address'];
        } else {
            $this->data['list'][$key]['bans']++;

            if (in_array($ip['address'], $this->ipList) === false) {
                $this->ipList[] = $ip['address'];
                $this->data['list'][$key]['ipCount']++;
            }
        }
    }
}
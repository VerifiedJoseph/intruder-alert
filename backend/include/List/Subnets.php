<?php

namespace List;

class Subnets extends AbstractList
{
    /** {@inheritDoc} */
    protected array $data = [
        'mostBanned' => '',
        'list' => []
    ];

    /** {@inheritDoc} */
    protected ?string $mostBannedParam = 'subnet';

    /** {@inheritDoc} */
    public function addIp(array $ip): void
    {
        $key = array_search($ip['network']['subnet'], array_column($this->data['list'], 'subnet'));
    
        if ($key === false) {
            $this->data['list'][] = [
                'subnet' => $ip['network']['subnet'],
                'version' => $ip['version'],
                'network' => $ip['network']['number'],
                'country' => $ip['country']['code'],
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
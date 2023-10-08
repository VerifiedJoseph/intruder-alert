<?php

namespace IntruderAlert\List;

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
        $subnet = $ip['network']['subnet'];

        if (array_key_exists($subnet, $this->data['list']) === false) {
            $this->data['list'][$subnet] = [
                'subnet' => $ip['network']['subnet'],
                'version' => $ip['version'],
                'network' => $ip['network']['number'],
                'country' => $ip['country']['code'],
                'bans' => 1,
                'ipCount' => 1,
            ];

            $this->ipList[$subnet][] = $ip['address'];
        } else {
            $this->data['list'][$subnet]['bans']++;

            if (in_array($ip['address'], $this->ipList[$subnet]) === false) {
                $this->ipList[$subnet][] = $ip['address'];
                $this->data['list'][$subnet]['ipCount']++;
            }
        }
    }
}

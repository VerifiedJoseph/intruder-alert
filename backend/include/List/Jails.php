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
        $jail = $ip['jail'];

        if (array_key_exists($jail, $this->data['list']) === false) {
            $this->data['list'][$jail] = [
                'name' => $ip['jail'],
                'ipCount' => 1,
                'bans' => 1
            ];

            $this->ipList[$jail][] = $ip['address'];
        } else {
            $this->data['list'][$jail]['bans']++;

            if (in_array($ip['address'], $this->ipList[$jail]) === false) {
                $this->ipList[$jail][] = $ip['address'];
                $this->data['list'][$jail]['ipCount']++;
            }
        }
    }
}

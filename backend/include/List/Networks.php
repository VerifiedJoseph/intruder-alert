<?php

declare(strict_types=1);

namespace IntruderAlert\List;

class Networks extends AbstractList
{
    /** {@inheritDoc} */
    protected array $data = [
        'mostBanned' => '',
        'list' => []
    ];

    /** {@inheritDoc} */
    protected ?string $mostBannedParam = 'number';

    /** {@inheritDoc} */
    public function addIp(array $ip): void
    {
        $number = $ip['network']['number'];

        if (array_key_exists($number, $this->data['list']) === false) {
            $this->data['list'][$number] = [
                'name' => $ip['network']['name'],
                'number' => $ip['network']['number'],
                'bans' => 1,
                'ipCount' => 1,
            ];

            $this->ipList[$number][] = $ip['address'];
        } else {
            $this->data['list'][$number]['bans']++;

            if (in_array($ip['address'], $this->ipList[$number]) === false) {
                $this->ipList[$number][] = $ip['address'];
                $this->data['list'][$number]['ipCount']++;
            }
        }
    }
}

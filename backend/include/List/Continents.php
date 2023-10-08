<?php

namespace IntruderAlert\List;

class Continents extends AbstractList
{
    /** {@inheritDoc} */
    protected array $data = [
        'mostBanned' => '',
        'list' => []
    ];

    /** {@inheritDoc} */
    protected ?string $mostBannedParam = 'code';

    /** {@inheritDoc} */
    public function addIp(array $ip): void
    {
        $code = $ip['continent']['code'];

        if (array_key_exists($code, $this->data['list']) === false) {
            $this->data['list'][$code] = [
                'code' => $ip['continent']['code'],
                'name' => $ip['continent']['name'],
                'bans' => 1,
                'ipCount' => 1
            ];

            $this->ipList[$code][] = $ip['address'];
        } else {
            $this->data['list'][ $code]['bans']++;

            if (in_array($ip['address'], $this->ipList[$code]) === false) {
                $this->ipList[$code][] = $ip['address'];
                $this->data['list'][$code]['ipCount']++;
            }
        }

        var_dump($this->ipList);
        exit();
    }
}

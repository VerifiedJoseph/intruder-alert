<?php

namespace List;

abstract class AbstractList
{
    /** @var array<string, mixed> $data */
    protected array $data = [
        'mostBanned' => null,
        'list' => []
    ];

    /** @var $ipList  IP addresses for this list */
    protected array $ipList = [];

    /** @var bool $calculateMostBanned Calculate most banned value for a list */
    protected bool $calculateMostBanned = true;

    /** @var ?string $mostBannedParam Data list parameter to use when calculating the most banned */
    protected ?string $mostBannedParam = null;

    /** @var 'bans'|'date' $orderItemsBy Data list parameter to order lists by */
    protected string $orderItemsBy = 'bans';

    /**
     * Get list
     * 
     * @return array<string, mixed>
     */
    public function get(): array
    {
        if ($this->calculateMostBanned === true) {
            $this->calculateMostBanned();
        }

        match ($this->orderItemsBy) {
            'bans' => $this->orderByBans(),
            'date' => $this->orderByDate()
        };

        return $this->data;
    }

    public function getIpList()
    {
        return $this->ipList;
    }

    /**
     * Add IP address
     * 
     * @param array<string, mixed> $ip IP address details
     */
    abstract public function addIp(array $ip): void;

    /**
     * Calculate most banned
     */
    final protected function calculateMostBanned(): void
    {
        $highest = 0;

        foreach ($this->data['list'] as $item) {
            if ($item['bans'] > $highest) {
                $highest = $item['bans'];
                $this->data['mostBanned'] = $item[$this->mostBannedParam];
            }
        }
    }

    /**
     * Order by bans
     */
    final protected function orderByBans(): void
    {
        $keys = array_column($this->data['list'], 'bans');
        array_multisort($keys, SORT_DESC, $this->data['list']);
    }

    /**
     * Order by date
     */
    protected function orderByDate(): void
    {
        usort($this->data['list'], function($a1, $a2) {
            $v1 = strtotime($a1['date']);
            $v2 = strtotime($a2['date']);
            return $v2 - $v1;
        });
    }
}
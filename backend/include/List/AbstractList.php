<?php

namespace IntruderAlert\List;

abstract class AbstractList
{
    /** @var array<string, mixed> $data */
    protected array $data = [
        'mostBanned' => null,
        'list' => []
    ];

    /** @var array<int|string, array<int, string>> $ipList IP addresses for this list */
    protected array $ipList = [];

    /** @var ?string $mostBannedParam Data list parameter to use when calculating the most banned */
    protected ?string $mostBannedParam = null;

    /**
     * Get list
     *
     * @return array<string, mixed>
     */
    public function get(): array
    {
        $this->calculateMostBanned();
        $this->orderList();

        $this->data['list'] = array_values($this->data['list']);

        return $this->data;
    }

    /**
     * Add IP address
     *
     * @param array<string, mixed> $ip IP address details
     */
    abstract public function addIp(array $ip): void;

    /**
     * Get item count for list
     */
    final public function getCount(): int
    {
        return count($this->data['list']);
    }

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
     * Order list by bans
     */
    protected function orderList(): void
    {
        $keys = array_column($this->data['list'], 'bans');
        array_multisort($keys, SORT_DESC, $this->data['list']);
    }
}

<?php

namespace List;

abstract class AbstractList
{
	/** @var array<string, mixed> $data */
	protected array $data = [
		'list' => []
	];

	/** @var array<string, boolean|string> $settings */
	protected array $settings = [
		'calculateMostBanned' => true,
		'orderBy' => 'none'
	];

	/**
	 * Get list
	 * 
	 * @return array<string, mixed>
	 */
	public function get(): array
	{
		if ($this->settings['calculateMostBanned'] === true) {
			$this->calculateMostBanned();
		}

		if ($this->settings['orderBy'] === 'bans') {
			$this->orderByBans();
		}

		if ($this->settings['orderBy'] === 'date') {
			$this->orderByDate();
		}

		return $this->data;
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
		$name = '';

		switch (get_class($this)) {
			case 'List\Address':
				$name = 'address';
				break;
			case 'List\Country':
				$name = 'code';
				break;
			case 'List\Jail':
				$name = 'name';
				break;
			case 'List\Network':
				$name = 'number';
				break;
		}

		foreach ($this->data['list'] as $item) {
			if ($item['bans'] > $highest) {
				$highest = $item['bans'];
				$this->data['mostBanned'] = $item[$name];
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
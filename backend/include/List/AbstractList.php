<?php

namespace List;

abstract class AbstractList
{
	/** @var array<string, mixed> $data */
	protected array $data = [
		'list' => []
	];

	/**
	 * Get list
	 * 
	 * @return array<string, mixed>
	 */
	public function get(): array
	{
		$this->calculateMostBanned();

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
}
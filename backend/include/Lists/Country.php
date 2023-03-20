<?php

namespace Lists;

class Country
{
	/** @var array<string, mixed> $data */
	private array $data = [
		'mostBanned' => '',
		'list' => []
	];

	/**
	 * Get list
	 * 
	 * @return array<string, mixed>
	 */
	public function get() {
		$this->calculateMostBanned();

		return $this->data;
	}

	/**
	 * Add IP address
	 * 
	 * @param array<string, mixed> $ip IP address details
	 */
	public function addIp(array $ip): void
	{
		$key = array_search($ip['country']['code'], array_column($this->data['list'], 'code'));
	
		if ($key === false) {
			$this->data['list'][] = [
				'code' => $ip['country']['code'],
				'name' => $ip['country']['name'],
				'bans' => 1,
				'ipCount' => 1,
				'ipList' => [$ip['address']]
			];
		} else {
			$this->data['list'][$key]['bans']++;

			if (in_array($ip['address'], $this->data['list'][$key]['ipList']) === false) {
				$this->data['list'][$key]['ipList'][] = $ip['address'];
				$this->data['list'][$key]['ipCount']++;
			}
		}
	}

	/**
	 * Calculate most banned
	 */
	private function calculateMostBanned(): void
	{
		$highest = 0;

		foreach ($this->data['list'] as $item) {
			if ($item['bans'] > $highest) {
				$highest = $item['bans'];
				$this->data['mostBanned'] = $item['code'];
			}
		}
	}
}
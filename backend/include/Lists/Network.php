<?php

namespace Lists;

use stdClass;

class Network {
	private array $data = [
		'mostBanned' => '',
		'list' => []
	];

	public function get() {
		$this->calculateMostBanned();

		return $this->data;
	}

	public function addIp(stdClass $ip): void
	{
		$key = array_search($ip->network->number, array_column($this->data['list'], 'number'));

		if ($key === false) {
			$this->data['list'][] = [
				'name' => $ip->network->name,
				'number' => $ip->network->number,
				'bans' => 1,
				'ipCount' => 1,
				'ipList' => [$ip->address]
			];
		} else {
			$this->data['list'][$key]['bans']++;

			if (in_array($ip->address, $this->data['list'][$key]['ipList']) === false) {
				$this->data['list'][$key]['ipList'][] = $ip->address;
				$this->data['list'][$key]['ipCount']++;
			}
		}
	}

	private function calculateMostBanned(): void
	{
		$highest = 0;

		foreach ($this->data['list'] as $item) {
			if ($item['bans'] > $highest) {
				$highest = $item['bans'];
				$this->data['mostBanned'] = $item['number'];
			}
		}
	}
}
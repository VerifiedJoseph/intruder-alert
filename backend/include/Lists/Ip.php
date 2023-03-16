<?php

namespace Lists;

use stdClass;

class Ip {
	private array $data = [
		'mostBanned' => '',
		'totalBans' => 0,
		'list' => []
	];

	public function get(): array
	{
		$this->calculateMostBanned();
		$this->orderByDate();

		return $this->data;
	}

	public function addIp(stdClass $ip)
	{
		$key = array_search($ip->address, array_column($this->data['list'], 'address'));

		if ($key === false) {
			$this->data['totalBans']++;

			$this->data['list'][] = [
				'address' => $ip->address,
				'bans' => 1,
				'country' =>  $ip->country->code,
				'network' => $ip->network->number,
				'events' => [[
					'timestamp' => $ip->timestamp,
					'jail' => $ip->jail
				]]
			];
		} else {
			$this->data['totalBans']++;
			$this->data['list'][$key]['bans']++;
			$this->data['list'][$key]['events'][] = [
				'timestamp' => $ip->timestamp,
				'jail' => $ip->jail
			];
		}
	}

	private function calculateMostBanned(): void
	{
		$highest = 0;

		foreach ($this->data['list'] as $item) {
			if ($item['bans'] > $highest) {
				$highest = $item['bans'];
				$this->data['mostBanned'] = $item['address'];
			}
		}
	}

	private function orderByDate()
	{
		$list = $this->data['list'];

		foreach ($list as $itemKey => $item) {
			usort($this->data['list'][$itemKey]['events'], function($a1, $a2) {
				$v1 = strtotime($a1['timestamp']);
				$v2 = strtotime($a2['timestamp']);
				return $v2 - $v1;
			});

			$this->data['list'][$itemKey]['firstSeen'] = $this->data['list'][$itemKey]['events'][0]['timestamp'];
		}

		usort($this->data['list'], function($a1, $a2) {
			$v1 = strtotime($a1['firstSeen']);
			$v2 = strtotime($a2['firstSeen']);
			return $v2 - $v1;
		});
	}
}
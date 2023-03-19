<?php

namespace Lists;

use stdClass;

class Date {
	private array $data = [
		'list' => []
	];

	public function get() {
		$this->orderByDate();

		return $this->data;
	}

	public function addIp(array $ip) {
		$date = date('Y-m-d', strtotime($ip['timestamp']));
		$key = array_search($date, array_column($this->data['list'], 'date'));

		if ($key === false) {
			$this->data['list'][] = [
				'date' => $date,
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

	private function orderByDate()
	{
		usort($this->data['list'], function($a1, $a2) {
			$v1 = strtotime($a1['date']);
			$v2 = strtotime($a2['date']);
			return $v2 - $v1;
		});
    }
}
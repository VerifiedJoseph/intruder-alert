<?php

namespace Lists;

use stdClass;

class Date {
	private array $data = [
		'list' => []
	];

	public function get() {
		return $this->data;
	}

	public function addIp(stdClass $ip) {
		$date = date('Y-m-d', strtotime($ip->timestamp));
		$key = array_search($date, array_column($this->data['list'], 'date'));

		if ($key === false) {
			$this->data['list'][] = [
				'date' => $date,
				'ipList' => [$ip->address]
			];
		} else {
			$this->data['list'][$key]['ipList'][] = $ip->address;
		}
	}
}
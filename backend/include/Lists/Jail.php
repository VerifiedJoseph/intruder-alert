<?php

namespace Lists;

use stdClass;

class Jail {
	private array $data = [
		'mostBanned' => '',
		'list' => []
	];

	public function get() {
		$this->calculateMostBanned();

		return $this->data;
	}

	public function addIp(array $ip)
	{
		$key = array_search($ip['jail'], array_column($this->data['list'], 'name'));

		if ($key === false) {
			$this->data['list'][] = [
				'name' => $ip['jail'],
				'ipList' => [$ip['address']],
				'ipCount' => 1,
				'bans' => 1
			];
		} else {
			$this->data['list'][$key]['bans']++;

			if (in_array($ip['address'], $this->data['list'][$key]['ipList']) === false) {
				$this->data['list'][$key]['ipList'][] = $ip['address'];
				$this->data['list'][$key]['ipCount']++;
			}
		}
	}

	private function calculateMostBanned(): void
	{
		$highest = 0;

		foreach ($this->data['list'] as $jail => $item) {
			if (count($item['ipList']) > $highest) {
				$highest = count($item['ipList']);
				$this->data['mostBanned'] = $jail;
			}
		}
	}
}
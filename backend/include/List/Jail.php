<?php

namespace List;

class Jail extends AbstractList
{
	/** @var array<string, mixed> $data */
	protected array $data = [
		'mostBanned' => '',
		'list' => []
	];

	/**
	 * Add IP address
	 * 
	 * @param array<string, mixed> $ip IP address details
	 */
	public function addIp(array $ip): void
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
}
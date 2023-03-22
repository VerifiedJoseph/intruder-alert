<?php

namespace List;

class Country extends AbstractList
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
}
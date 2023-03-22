<?php

namespace List;

class Network  extends AbstractList
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
		$key = array_search($ip['network']['number'], array_column($this->data['list'], 'number'));

		if ($key === false) {
			$this->data['list'][] = [
				'name' => $ip['network']['name'],
				'number' => $ip['network']['number'],
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
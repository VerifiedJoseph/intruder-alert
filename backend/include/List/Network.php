<?php

namespace List;

class Network  extends AbstractList
{
	/** @var array<string, mixed> $data */
	protected array $data = [
		'mostBanned' => '',
		'list' => []
	];

	/** @var array<int, string> $ipList  IP addresses for this list */
	protected array $ipList = [];

	/** @var array<string, boolean|string> $settings */
	protected array $settings = [
		'calculateMostBanned' => true,
		'orderBy' => 'bans'
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
			];

			$this->ipList[] = $ip['address'];
		} else {
			$this->data['list'][$key]['bans']++;

			if (in_array($ip['address'], $this->ipList) === false) {
				$this->ipList[] = $ip['address'];
				$this->data['list'][$key]['ipCount']++;
			}
		}
	}
}
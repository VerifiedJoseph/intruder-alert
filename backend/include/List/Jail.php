<?php

namespace List;

class Jail extends AbstractList
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
		$key = array_search($ip['jail'], array_column($this->data['list'], 'name'));

		if ($key === false) {
			$this->data['list'][] = [
				'name' => $ip['jail'],
				'ipCount' => 1,
				'bans' => 1
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
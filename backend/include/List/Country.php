<?php

namespace List;

class Country extends AbstractList
{
	/** {@inheritDoc} */
	protected array $data = [
		'mostBanned' => '',
		'list' => []
	];

	/** {@inheritDoc} */
	protected array $ipList = [];

	/** {@inheritDoc} */
	protected array $settings = [
		'calculateMostBanned' => true,
		'orderBy' => 'bans'
	];

	/** {@inheritDoc} */
	public function addIp(array $ip): void
	{
		$key = array_search($ip['country']['code'], array_column($this->data['list'], 'code'));
	
		if ($key === false) {
			$this->data['list'][] = [
				'code' => $ip['country']['code'],
				'name' => $ip['country']['name'],
				'bans' => 1,
				'ipCount' => 1
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
<?php

use Helper\File;
use Helper\Json;

class Report
{
	private array $lists = [];

	public function __construct(array $lists)
	{
		$this->lists = $lists;
	}

	public function generate(): void
	{
		$data = $this->lists;
		$data['stats'] =  $this->createStats();
		$data['updated'] = date('Y-m-d H:i:s');
		$data['dataSince'] = $this->getDataSinceDate();

		File::write(
			'../frontend/data.json',
			Json::encode($data)
		);
	}

	private function createStats(): array
	{
		$data = [];
		$data['totals']['ip'] = count($this->lists['address']['list']);
		$data['totals']['network'] = count($this->lists['network']['list']);
		$data['totals']['country'] = count($this->lists['country']['list']);
		$data['totals']['date'] = count($this->lists['date']['list']);
		$data['totals']['jail'] = count($this->lists['jail']['list']);

		$data['bans']['total'] = $this->lists['address']['totalBans'];
		$data['bans']['today'] = 0;
		$data['bans']['yesterday'] = 0;
		$data['bans']['perDay'] = 0;

		$key = array_search(
			date('Y-m-d'),
			array_column($this->lists['date']['list'], 'date')
		);
		
		if ($key !== false) {
			$data['bans']['today'] = $this->lists['date']['list'][$key]['bans'];
		}

		$key = array_search(
			date('Y-m-d', strtotime('-1 days')),
			array_column($this->lists['date']['list'], 'date')
		);
		
		if ($key !== false) {
			$data['bans']['yesterday'] = $this->lists['date']['list'][$key]['bans'];
		}

		$dayCount = count($this->lists['date']['list']);
		$data['bans']['perDay'] = floor($this->lists['address']['totalBans'] / $dayCount);

		return $data;
	}

	private function getDataSinceDate()
	{
		$key = array_key_last($this->lists['address']['list']);
		return date('Y-m-d', strtotime($this->lists['address']['list'][$key]['firstSeen']));
	}
}
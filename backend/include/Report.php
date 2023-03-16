<?php

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

		file_put_contents(
			'../frontend/data.json', 
			json_encode($data)
		);
	}

	private function createStats(): array
	{
		$data = [];
		$data['totals']['ip'] = count($this->lists['ip']['list']);
		$data['totals']['network'] = count($this->lists['network']['list']);
		$data['totals']['country'] = count($this->lists['country']['list']);
		$data['totals']['date'] = count($this->lists['date']['list']);
		$data['totals']['jail'] = count($this->lists['jail']['list']);

		$data['bans']['total'] = $this->lists['ip']['totalBans'];
		$data['bans']['today'] = 0;
		$data['bans']['yesterday'] = 0;
		$data['bans']['perDay'] = 0;

		$key = array_search(
			date('Y-m-d'),
			array_column($this->lists['date']['list'], 'date')
		);
		
		if ($key !== false) {
			$data['bans']['today'] = count($this->lists['date']['list'][$key]['ipList']);
		}

		$key = array_search(
			date('Y-m-d', strtotime('-1 days')),
			array_column($this->lists['date']['list'], 'date')
		);
		
		if ($key !== false) {
			$data['bans']['yesterday'] = count($this->lists['date']['list'][$key]['ipList']);
		}

		$dayCount = count($this->lists['date']['list']);
		$data['bans']['perDay'] = floor($this->lists['ip']['totalBans'] / $dayCount);

		return $data;
	}

	private function getDataSinceDate()
	{
		$key = array_key_last($this->lists['ip']['list']);
		return date('Y-m-d', strtotime($this->lists['ip']['list'][$key]['firstSeen']));
	}
}
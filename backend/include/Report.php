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
		$data['updated'] = date('Y-m-d G:i:s');
		$data['stats'] =  $this->createGlobalStats();

		file_put_contents(
			'../frontend/data.json', 
			json_encode($data)
		);
	}

	private function createGlobalStats(): array
	{
		$data = [];
		$data['bans']['total'] = $this->lists['ip']['totalBans'];
		$data['bans']['today'] = 0;
		$data['bans']['yesterday'] = 0;
		$data['bans']['perDay'] = 0;

		$key = array_search(
			date('Y-m-d'),
			array_column($this->lists['date']['list'], 'date')
		);
		
		if ($key !== false) {
			$data['bans']['today'] = count($this->lists['date']['list'][$key]);
		}

		$key = array_search(
			date('Y-m-d', strtotime('-1 days')),
			array_column($this->lists['date']['list'], 'date')
		);
		
		if ($key !== false) {
			$data['bans']['yesterday'] = count($this->lists['date']['list'][$key]);
		}

		$dayCount = count($this->lists['date']['list']);
		$data['bans']['perDay'] = floor($this->lists['ip']['totalBans'] / $dayCount);

		return $data;
	}
}
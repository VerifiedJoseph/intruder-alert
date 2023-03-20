<?php

use Helper\File;
use Helper\Json;

/**
 * Class for generating the report JSON
 */
class Report
{
	/** @var array<string, mixed> $lists */
	private array $lists = [];

	/**
	 * 
	 * @param array<string, mixed> $lists
	 */
	public function __construct(array $lists)
	{
		$this->lists = $lists;
	}

	/**
	 * Generate JSON file
	 */
	public function generate(): void
	{
		$data = $this->lists;
		$data['stats'] =  $this->createStats();
		$data['updated'] = date('Y-m-d H:i:s');
		$data['dataSince'] = $this->getDataSinceDate();

		File::write(
			'./data/data.json',
			Json::encode($data)
		);
	}

	/**
	 * Create stats
	 * 
	 * @return array<string, mixed>
	 */
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

	/**
	 * Get data since date
	 */
	private function getDataSinceDate(): string
	{
		$key = array_key_last($this->lists['address']['list']);
		return date('Y-m-d', strtotime($this->lists['address']['list'][$key]['firstSeen']));
	}
}
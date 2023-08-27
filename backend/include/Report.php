<?php

use Helper\File;
use Helper\Json;
use Helper\Logger;
use Helper\Output;

/**
 * Class for generating the report JSON
 */
class Report
{
    /** @var array<string, mixed> $lists */
    private array $lists = [];

    /** @var string $path Path to save the generated report */
    private string $path = '';

    /** @var bool $disableCharts Status for disable charts in frontend */
    private bool $disableCharts = false;

    /** @var string $timezone Timezone */
    private string $timezone = '';

    /**
     * 
     * @param array<string, mixed> $lists
     */
    public function __construct(array $lists, string $path, bool $disableCharts = false, string $timezone)
    {
        $this->lists = $lists;
        $this->path = $path;
        $this->disableCharts = $disableCharts;
        $this->timezone = $timezone;
    }

    /**
     * Generate JSON file
     */
    public function generate(): void
    {
        $data = $this->lists;
        $data['stats'] = $this->createStats();
        $data['plots'] = $this->createPlots();
        $data['updated'] = date('Y-m-d H:i:s');
        $data['dataSince'] = $this->getDataSinceDate();
        $data['log'] = Logger::getEntries();
        $data['log'][] = 'Last run: ' . $data['updated'];
        $data['settings']['disableCharts'] = $this->disableCharts;
        $data['settings']['timezone'] = $this->timezone;

        File::write(
            $this->path,
            Json::encode($data)
        );

        Output::text('Created report JSON file: ' . $this->path);
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
     * Create chart plots
     * 
     * @return array<string, array<string, mixed>>
     */
    private function createPlots(): array
    {
        $data = [];
        $plots = new Plots();
        $data['last24hours'] = $plots->last24Hours($this->lists['address']['list']);
        $data['last7days'] = $plots->lastXDays($this->lists['address']['list'], days: 7);
        $data['last30days'] = $plots->lastXDays($this->lists['address']['list'], days: 30);

        return $data;
    }

    /**
     * Get data since date
     */
    private function getDataSinceDate(): string
    {
        $key = array_key_last($this->lists['date']['list']);
        return $this->lists['date']['list'][$key]['date'];
    }
}
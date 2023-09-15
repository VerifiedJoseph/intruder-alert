<?php

namespace IntruderAlert;

use IntruderAlert\Helper\File;
use IntruderAlert\Helper\Json;
use IntruderAlert\Helper\Logger;
use IntruderAlert\Helper\Output;

/**
 * Class for generating the report JSON
 */
class Report
{
    /** @var array<string, mixed> $lists */
    private array $lists = [];

    /** @var string $path Path to save the generated report */
    private string $path = '';

    /** @var bool $enableCharts Status for dashboard charts */
    private bool $enableCharts = true;

    /** @var bool $enableUpdates Status for automatic dashboard updates */
    private bool $enableUpdates = true;

    /** @var string $timezone Timezone */
    private string $timezone = '';

    /** @var string $version Intruder alert version */
    private string $version = '';

    /**
     *
     * @param array<string, mixed> $lists
     */
    public function __construct(
        array $lists,
        string $path,
        string $timezone,
        string $version,
        bool $charts,
        bool $updates
    ) {
        $this->lists = $lists;
        $this->path = $path;
        $this->timezone = $timezone;
        $this->version = $version;
        $this->enableCharts = $charts;
        $this->enableUpdates = $updates;
    }

    /**
     * Generate JSON file
     */
    public function generate(): void
    {
        $data = $this->lists;
        $data['stats'] = $this->createStats();
        $data['updated'] = date('Y-m-d H:i:s');
        $data['dataSince'] = $this->getDataSinceDate();
        $data['log'] = Logger::getEntries();
        $data['log'][] = 'Last run: ' . $data['updated'];
        $data['settings']['enableCharts'] = $this->enableCharts;
        $data['settings']['enableUpdates'] = $this->enableUpdates;
        $data['settings']['timezone'] = $this->timezone;
        $data['settings']['version'] = $this->version;

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
     * Get data since date
     */
    private function getDataSinceDate(): string
    {
        $key = array_key_last($this->lists['date']['list']);
        return $this->lists['date']['list'][$key]['date'];
    }
}

<?php

declare(strict_types=1);

namespace IntruderAlert;

use DateTimeZone;
use DateTimeImmutable;
use IntruderAlert\Logger;
use IntruderAlert\Helper\File;
use IntruderAlert\Helper\Json;
use IntruderAlert\Helper\Output;

/**
 * Class for generating the report JSON
 */
class Report
{
    /** @var Logger $logger */
    private Logger $logger;

    /** @var array<string, mixed> $lists */
    private array $lists = [];

    /** @var array<string, int> $counts List item counts */
    private array $counts = [];

    /** @var string $path Path to save the generated report */
    private string $path = '';

    /** @var DateTime $data */
    private DateTimeImmutable $date;

    /**
     * @param array<string, mixed> $lists
     * @param array<string, mixed> $counts
     * @param string $path Data file path
     * @param string $timezone Dashboard timezone
     * @param Logger $logger Logger class instance
     */
    public function __construct(
        array $lists,
        array $counts,
        string $path,
        string $timezone,
        Logger $logger
    ) {
        $this->lists = $lists;
        $this->counts = $counts;
        $this->path = $path;
        $this->logger = $logger;
        $this->date = new DateTimeImmutable(
            'now',
            new DateTimeZone($timezone)
        );
    }

    /**
     * Generate JSON file
     */
    public function generate(): void
    {
        $data = [];
        $data['dataset'] = $this->lists;
        $data['dataset']['stats'] = $this->createStats();
        $data['updated'] = $this->date->format('Y-m-d H:i:s');
        $data['hash'] = sha1($data['updated']);
        $data['dataSince'] = $this->getDataSinceDate();
        $data['log'] = $this->logger->getEntries();
        $data['log'][] = 'Last run: ' . $this->date->format('Y-m-d H:i:s e');

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
        $data['totals']['ip'] = $this->counts['address'];
        $data['totals']['network'] = $this->counts['network'];
        $data['totals']['country'] = $this->counts['country'];
        $data['totals']['date'] = $this->counts['date'];
        $data['totals']['jail'] = $this->counts['jail'];

        $data['bans']['total'] = $this->counts['totalBans'];
        $data['bans']['today'] = 0;
        $data['bans']['yesterday'] = 0;
        $data['bans']['perDay'] = 0;

        $key = array_search(
            $this->date->format('Y-m-d'),
            array_column($this->lists['date']['list'], 'date')
        );

        if ($key !== false) {
            $data['bans']['today'] = $this->lists['date']['list'][$key]['bans'];
        }

        $key = array_search(
            $this->date->modify('-1 days')->format('Y-m-d'),
            array_column($this->lists['date']['list'], 'date')
        );

        if ($key !== false) {
            $data['bans']['yesterday'] = $this->lists['date']['list'][$key]['bans'];
        }

        $data['bans']['perDay'] = floor($this->counts['totalBans'] / $this->counts['date']);

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

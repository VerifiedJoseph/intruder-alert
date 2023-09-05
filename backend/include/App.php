<?php

use Helper\File;
use Helper\Json;
use Exception\ReportException;
use Helper\Output;

class App
{
    /** @var Config $config */
    private Config $config;

    /** @var Lists $lists */
    private Lists $lists;

    /** @var string $dataFilepath Report data filepath */
    private string $dataFilepath = 'data/data.json';

    /** @var string $cacheFilepath Cache filepath */
    private string $cacheFilepath = 'data/cache.json';

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->lists = new Lists();
    }

    /**
     * Run app
     */
    public function run(): void
    {
        try {
            $this->databaseUpdate();
            $this->processLogs();
            $this->generateReport();
        } catch (ReportException $err) {
            $this->generateErrorReport($err->getMessage());
        }
    }

    /**
     * Get report JSON
     */
    public function getJsonReport(): string
    {
        $path = $this->config->getPath($this->dataFilepath);

        if (File::exists($path) === false) {
            return Json::encode([
                'error' => true,
                'message' => 'No data. Is the backend script setup?'
            ]);
        }

        $lastUpdated = $_POST['lastUpdated'] ?? '';
        if ($lastUpdated !== '') {
            $data = Json::decode(File::read($path));

            if (strtotime($data['updated']) > strtotime($lastUpdated)) {
                $data['hasUpdates'] = true;
                return Json::encode($data);
            }

            return Json::encode([]);
        }

        return File::read($path);
    }

    /**
     * Process logs
     */
    private function processLogs(): void
    {
        $lookup = new Database\Lookup();
        $lookup->setNetworkDatabase($this->config->getAsnDatabasePath());
        $lookup->setCountryDatabase($this->config->getCountryDatabasePath());

        $timer = new Timer();
        $timer->start();

        $logs = new Logs($this->config);
        $cache = new Cache(
            $this->config->getPath($this->cacheFilepath)
        );

        foreach ($logs->process() as $line) {
            if ($cache->hasItem($line['ip']) === true) {
                $this->lists->addIp(
                    array_merge($cache->getItem($line['ip']), $line)
                );
            } else {
                $ip = new Ip($line['ip']);
                $ip->setJail($line['jail']);
                $ip->setTimestamp($line['timestamp']);
                $ip->setCountry($lookup->country($line['ip']));
                $ip->setNetwork($lookup->network($line['ip']));

                $cache->addItem($ip->getDetails());
                $this->lists->addIp($ip->getDetails());
            }
        }

        $cache->save();
        $timer->stop();
        Output::text(sprintf('Time taken: %ss', $timer->getTime()), log: true);
    }

    /**
     * Generate report
     */
    private function generateReport(): void
    {
        $report = new Report(
            $this->lists->get(),
            $this->config->getPath($this->dataFilepath),
            $this->config->getTimezone(),
            $this->config->getChartsStatus(),
            $this->config->getDashUpdatesStatus(),
        );
        $report->generate();
    }

    /**
     * Generate error report
     * 
     * @param string $message Error message
     */
    private function generateErrorReport(string $message): void
    {
        $data = [
            'error' => true,
            'message' => $message,
            'updated' => date('Y-m-d H:i:s')
        ];

        File::write(
            $this->config->getPath($this->dataFilepath),
            Json::encode($data)
        );
    }

    private function databaseUpdate(): void
    {
        $update = new Database\Update($this->config);
        $update->run();
    }
}

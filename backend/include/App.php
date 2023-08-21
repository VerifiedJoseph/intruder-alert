<?php

use Helper\File;
use Helper\Json;
use Exception\ReportException;
use Helper\Logger;

class App
{
    /** @var Lists $lists */
    private Lists $lists;

    /** @var string $dataFilepath Report data filepath */
    private string $dataFilepath = 'data/data.json';

    /** @var string $cacheFilepath Cache filepath */
    private string $cacheFilepath = 'data/cache.json';

    public function __construct()
    {
        $this->lists = new Lists();
    }

    /**
     * Run app
     */
    public function run(): void
    {
        try {
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
        $path = Config::getPath($this->dataFilepath);

        if (File::exists($path) === false) {
            return Json::encode([
                'error' => true,
                'message' => 'No data. Is the backend script setup?'
            ]);
        }

        return File::read($path);
    }

    /**
     * Process logs
     */
    private function processLogs(): void
    {
        $timer = new Timer();
        $timer->start();

        $logs = new Logs(Config::getLogFolder());
        $cache = new Cache(
            Config::getPath($this->cacheFilepath)
        );

        foreach ($logs->process() as $line) {
            if ($cache->hasItem($line['ip']) === true) {
                $this->lists->addIp(
                    array_merge($cache->getItem($line['ip']), $line)
                );
            } else {
                $ip = new Ip(
                    $line['ip'],
                    $line['jail'],
                    $line['timestamp']
                );

                $cache->addItem($ip->getDetails());
                $this->lists->addIp($ip->getDetails());
            }
        }

        $cache->save();
        $timer->stop();
        Logger::addEntry(sprintf('Time taken: %ss', $timer->getTime()));
    }

    /**
     * Generate report
     */
    private function generateReport(): void
    {
        $report = new Report($this->lists->get());
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
            Config::getPath($this->dataFilepath),
            Json::encode($data)
        );
    }
}

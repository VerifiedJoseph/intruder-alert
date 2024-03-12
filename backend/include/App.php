<?php

namespace IntruderAlert;

use IntruderAlert\Logs\Logs;
use IntruderAlert\Helper\File;
use IntruderAlert\Helper\Json;
use IntruderAlert\Helper\Timer;
use IntruderAlert\Helper\Output;
use IntruderAlert\Exception\ReportException;

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

        $data = Json::decode(File::read($path));

        $lastUpdated = $_POST['lastUpdated'] ?? '';
        if ($lastUpdated !== '') {
            if (strtotime($data['updated']) > strtotime($lastUpdated)) {
                $data['hasUpdates'] = true;
            } else {
                return Json::encode([]);
            }
        }

        if ($this->config->getDashDaemonLogStatus() === false) {
            unset($data['log']);
        }

        $data['settings'] = [
            'features' => [
                'charts' => $this->config->getChartsStatus(),
                'updates' => $this->config->getDashUpdatesStatus(),
                'daemonLog' => $this->config->getDashDaemonLogStatus()
            ],
            'timezone' => $this->config->getTimezone(),
            'version' => $this->config->getVersion()
        ];

        return Json::encode($data);
    }

    /**
     * Process logs
     */
    private function processLogs(): void
    {
        $networkDatabase = new Database\Network($this->config->getAsnDatabasePath());
        $countryDatabase = new Database\Country($this->config->getCountryDatabasePath());

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
                $region = $countryDatabase->lookup($line['ip']);
                $network = $networkDatabase->lookup($line['ip']);

                $ip = new Ip($line['ip']);
                $ip->setJail($line['jail']);
                $ip->setTimestamp($line['timestamp']);
                $ip->setCountry($region['country']);
                $ip->setContinent($region['continent']);
                $ip->setNetwork($network);

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
            $this->lists->getCounts(),
            $this->config->getPath($this->dataFilepath),
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
        $fetch = new Fetch($this->config->getUseragent());
        $helper = new Database\Updater\Helper();
        $updater = new Database\Updater\Updater($this->config, $fetch, $helper);
        $updater->run();
    }
}

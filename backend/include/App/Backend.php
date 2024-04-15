<?php

namespace IntruderAlert\App;

use DateTimeImmutable;
use DateTimeZone;
use IntruderAlert\Ip;
use IntruderAlert\Cache;
use IntruderAlert\Fetch;
use IntruderAlert\Report;
use IntruderAlert\Database;
use IntruderAlert\Logs\Logs;
use IntruderAlert\Helper\File;
use IntruderAlert\Helper\Json;
use IntruderAlert\Helper\Timer;
use IntruderAlert\Exception\LogsException;

class Backend extends App
{
    /**
     * Run app
     */
    public function run(): void
    {
        try {
            $this->databaseUpdate();
            $this->processLogs();
            $this->generateReport();
        } catch (LogsException $err) {
            $this->generateErrorReport($err->getMessage());
        }
    }

    /**
     * Process logs
     */
    private function processLogs(): void
    {
        $networkDatabase = new Database\Network($this->config->getAsnDatabasePath(), $this->logger);
        $countryDatabase = new Database\Country($this->config->getCountryDatabasePath(), $this->logger);

        $timer = new Timer();
        $timer->start();

        $logs = new Logs($this->config, $this->logger);
        $cache = new Cache($this->config->getCacheFilePath());

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
        $this->logger->addEntry(sprintf('Time taken: %ss', $timer->getTime()));
    }

    /**
     * Generate report
     */
    private function generateReport(): void
    {
        $report = new Report(
            $this->lists->get(),
            $this->lists->getCounts(),
            $this->config->getDataFilePath(),
            $this->config->getTimezone(),
            $this->logger
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
        $date = new DateTimeImmutable(
            'now',
            new DateTimeZone($this->config->getTimezone())
        );

        $data = [
            'error' => true,
            'message' => $message,
            'updated' => $date->format('Y-m-d H:i:s')
        ];

        File::write(
            $this->config->getDataFilePath(),
            Json::encode($data)
        );
    }

    /**
     * Update GeoIp databases
     */
    private function databaseUpdate(): void
    {
        $fetch = new Fetch($this->config->getUseragent());
        $updater = new Database\Updater\Updater($this->config, $fetch, $this->logger);
        $updater->run();
    }
}

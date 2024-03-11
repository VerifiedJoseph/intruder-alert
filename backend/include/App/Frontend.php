<?php

namespace IntruderAlert\App;

use IntruderAlert\Helper\File;
use IntruderAlert\Helper\Json;

class Frontend extends App
{
    /**
     * Run app
     */
    public function run(): string
    {
        return $this->getJsonReport();
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
}

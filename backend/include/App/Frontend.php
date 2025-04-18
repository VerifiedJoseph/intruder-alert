<?php

declare(strict_types=1);

namespace IntruderAlert\App;

use IntruderAlert\Helper\File;
use IntruderAlert\Helper\Json;

class Frontend extends AbstractApp
{
    /**
     * Get report JSON
     */
    public function getJsonReport(): string
    {
        $path = $this->config->getDataFilePath();

        if (File::exists($path) === false) {
            return Json::encode([
                'error' => true,
                'message' => 'No data. Is the backend script setup?'
            ]);
        }

        $data = [];
        $data['dataset'] = Json::decode(File::read($path));

        $hash = $_POST['hash'] ?? '';
        if ($hash !== '') {
            if ($hash !== $data['dataset']['hash']) {
                $data['hasUpdates'] = true;
            } else {
                return Json::encode([]);
            }
        }

        if ($this->config->getDashDaemonLogStatus() === false) {
            unset($data['dataset']['log']);
        }

        $data['settings'] = [
            'features' => [
                'charts' => $this->config->getChartsStatus(),
                'updates' => $this->config->getDashUpdatesStatus(),
                'daemonLog' => $this->config->getDashDaemonLogStatus()
            ],
            'defaults' => [
                'chart' => $this->config->getDashDefaultChart(),
                'pageSize' => $this->config->getDashPageSize()
            ],
            'timezone' => $this->config->getTimezone(),
            'version' => $this->config->getVersion()
        ];

        return Json::encode($data);
    }
}

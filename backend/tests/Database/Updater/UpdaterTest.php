<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Config;
use IntruderAlert\Fetch;
use IntruderAlert\Database\Updater\Updater;

class UpdaterTest extends TestCase
{
    public function testWithNoMindLicenseKey(): void
    {
        $this->expectNotToPerformAssertions();

        $config = new Config();
        $fetch = new Fetch($config->getUseragent());
        $updater = new Updater($config, $fetch);
        $updater->run();
    }
}

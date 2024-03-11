<?php

namespace IntruderAlert\App;

use IntruderAlert\Config;
use IntruderAlert\Lists;

abstract class App
{
    /** @var Config $config */
    protected Config $config;

    /** @var Lists $lists */
    protected Lists $lists;

    /** @var string $dataFilepath Report data filepath */
    protected string $dataFilepath = 'data/data.json';

    /** @var string $cacheFilepath Cache filepath */
    protected string $cacheFilepath = 'data/cache.json';

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->lists = new Lists();
    }

    abstract public function run(): mixed;
}

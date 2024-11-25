<?php

namespace IntruderAlert\App;

use IntruderAlert\Config;
use IntruderAlert\Logger;
use IntruderAlert\Lists;

abstract class AbstractApp
{
    /** @var Config $config */
    protected Config $config;

    /** @var Logger $logger */
    protected Logger $logger;

    /** @var Lists $lists */
    protected Lists $lists;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->logger = new Logger(
            $this->config->getTimezone(),
            $this->config->getLoggingLevel()
        );

        $this->lists = new Lists();
    }
}

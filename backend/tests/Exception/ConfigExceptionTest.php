<?php

use PHPUnit\Framework\Attributes\CoversClass;
use IntruderAlert\Exception\ConfigException;

#[CoversClass(ConfigException::class)]
class ConfigExceptionTest extends AbstractTestCase
{
    public function testConfigException(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Config error: testing');

        throw new ConfigException('testing');
    }
}

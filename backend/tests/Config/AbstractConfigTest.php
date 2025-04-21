<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\WithEnvironmentVariable;
use IntruderAlert\Config\AbstractConfig;

#[CoversClass(AbstractConfig::class)]
class AbstractConfigTest extends AbstractTestCase
{
    /**
     * Test `isEnvBoolean`
     */
    #[WithEnvironmentVariable('IA_TEST', 'true')]
    public function testIsEnvBoolean(): void
    {
        $class = new class () extends AbstractConfig {
        };
        $this->assertTrue($class->isEnvBoolean('TEST'));
    }

    /**
     * Test `getEnv`
     */
    #[WithEnvironmentVariable('IA_TEST', 'Hello World')]
    public function testGetEnv(): void
    {
        $class = new class () extends AbstractConfig {
        };
        $this->assertEquals('Hello World', $class->getEnv('TEST'));
    }

    /**
     * Test `getEnv` with no environment variable
     */
    public function testGetEnvEmptyValue(): void
    {
        $class = new class () extends AbstractConfig {
        };
        $this->assertEquals('', $class->getEnv('TEST_1'));
    }
}

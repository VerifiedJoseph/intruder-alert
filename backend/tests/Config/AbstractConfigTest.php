<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use IntruderAlert\Config\AbstractConfig;

#[CoversClass(AbstractConfig::class)]
class AbstractConfigTest extends AbstractTestCase
{
    public function setUp(): void
    {
        // Unset environment variables before each test
        putenv('IA_TEST');
    }

    /**
     * Test `isEnvBoolean`
     */
    public function testIsEnvBoolean(): void
    {
        putenv('IA_TEST=true');

        $class = new class () extends AbstractConfig {
        };
        $this->assertTrue($class->isEnvBoolean('TEST'));
    }

    /**
     * Test `getEnv`
     */
    public function testGetEnv(): void
    {
        putenv('IA_TEST=Hello World');

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

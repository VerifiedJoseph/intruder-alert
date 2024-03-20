<?php

use IntruderAlert\Config\Base;

class BaseTest extends AbstractTestCase
{
    private static $class;

    public static function setupBeforeClass(): void
    {
        self::$class = self::createClass();
    }

    public function setUp(): void
    {
        // Unset environment variables before each test
        putenv('IA_TEST');
    }

    /**
     * Test `getEnv`
     */
    public function testGetEnv(): void
    {
        putenv('IA_TEST=Hello World');
        $this->assertEquals('Hello World', self::$class->getEnv('TEST'));
    }

    /**
     * Test `getEnv` with no environment variable
     */
    public function testGetEnvEmptyValue(): void
    {
        $this->assertEquals('', self::$class->getEnv('TEST_1'));
    }

    private static function createClass()
    {
        return new class () extends Base
        {
            public function getEnv(string $name): string
            {
                return parent::getEnv($name);
            }
        };
    }
}

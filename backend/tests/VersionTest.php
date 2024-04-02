<?php

use PHPUnit\Framework\Attributes\CoversClass;
use IntruderAlert\Version;

#[CoversClass(Version::class)]
class VersionTest extends AbstractTestCase
{
    private static string $expected = '1.14.1';

    public static function setUpBeforeClass(): void
    {
        $reflection = new ReflectionClass(new Version());
        self::$expected = $reflection->getProperty('version')->getValue();
    }

    public function testGet(): void
    {
        $this->assertEquals(self::$expected, Version::get());
    }
}

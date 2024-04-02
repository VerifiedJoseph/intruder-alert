<?php

use PHPUnit\Framework\Attributes\CoversClass;
use IntruderAlert\Version;

#[CoversClass(Version::class)]
class VersionTest extends AbstractTestCase
{
    public function testGet(): void
    {
        $reflection = new ReflectionClass(new Version());
        $expected = $reflection->getProperty('version')->getValue();

        $this->assertEquals($expected, Version::get());
    }
}

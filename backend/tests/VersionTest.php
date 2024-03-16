<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Version;

class VersionTest extends TestCase
{
    public function testGet(): void
    {
        $reflection = new ReflectionClass(new Version());
        $expected = $reflection->getProperty('version')->getValue();

        $this->assertEquals($expected, Version::get());
    }
}

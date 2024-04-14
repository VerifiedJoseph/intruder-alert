<?php

use PHPUnit\Framework\Attributes\CoversClass;
use IntruderAlert\Helper\Convert;

#[CoversClass(Convert::class)]
class ConvertTest extends AbstractTestCase
{
    public function testTimezone(): void
    {
        $expected = '2024-04-14 16:00:00';
        $actual = Convert::timezone('2024-04-14 15:00:00', 'UTC', 'Europe/London');

        $this->assertEquals($expected, $actual);
    }
}

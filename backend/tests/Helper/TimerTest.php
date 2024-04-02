<?php

use PHPUnit\Framework\Attributes\CoversClass;
use IntruderAlert\Helper\Timer;

#[CoversClass(Timer::class)]
class TimerTest extends AbstractTestCase
{
    public function testTimer(): void
    {
        $timer = new Timer();
        $timer->start();

        usleep(20000);

        $timer->stop();

        $this->assertEquals(0.02, $timer->getTime());
    }
}

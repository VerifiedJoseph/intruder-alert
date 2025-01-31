<?php

declare(strict_types=1);

namespace IntruderAlert\Helper;

class Timer
{
    private float $start = 0;
    private float $end = 0;

    public function start(): void
    {
        $this->start = microtime(true);
    }

    public function stop(): void
    {
        $this->end = microtime(true);
    }

    public function getTime(): float
    {
        return round($this->end - $this->start, 3);
    }
}

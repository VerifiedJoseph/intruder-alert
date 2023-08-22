<?php

namespace Exception;

class ConfigException extends \Exception
{
    public function __construct(string $message, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(sprintf('Config error: %s', $message), $code, $previous);
    }
}

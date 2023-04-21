<?php

namespace Exception;

class ConfigException extends \Exception
{
    public function __construct(string $message, int $code = 0, \Throwable $previous = null)
    {
        $message = '[Config error] ' . $message;

        parent::__construct($message, $code, $previous);
    }
}

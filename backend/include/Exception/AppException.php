<?php

namespace IntruderAlert\Exception;

class AppException extends \Exception
{
    public function __construct(string $message, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(sprintf('App error: %s', $message), $code, $previous);
    }
}

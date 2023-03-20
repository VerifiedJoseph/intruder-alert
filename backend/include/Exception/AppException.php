<?php

namespace Exception;

class AppException extends \Exception
{
	public function __construct(string $message, int $code = 0, \Throwable $previous = null)
    {
        $message = '[App error] ' . $message;

        parent::__construct($message, $code, $previous);
    }
}

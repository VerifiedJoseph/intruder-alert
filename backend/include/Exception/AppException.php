<?php

declare(strict_types=1);

namespace IntruderAlert\Exception;

class AppException extends \Exception
{
    public function __construct(string $message, int $code = 0)
    {
        parent::__construct(sprintf('App error: %s', $message), $code);
    }
}

<?php

use PHPUnit\Framework\Attributes\CoversClass;
use IntruderAlert\Exception\AppException;

#[CoversClass(AppException::class)]
class AppExceptionTest extends AbstractTestCase
{
    public function testAppException(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('App error: testing');

        throw new AppException('testing');
    }
}
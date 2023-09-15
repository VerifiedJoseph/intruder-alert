<?php

use PHPUnit\Framework\TestCase;
use IntruderAlert\Helper\Json;
use IntruderAlert\Exception\AppException;

class JsonTest extends TestCase
{
    public function testEncodeValid(): void
    {
        self::assertEquals('{"foo":"bar"}', Json::encode(['foo' => 'bar']));
    }

    public function testDecodeValid(): void
    {
        $expected = ['foo' => 'bar'];
        self::assertEquals($expected, Json::decode('{"foo": "bar"}'));
    }

    public function testDecodeInvalid(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('JSON Error: Syntax error');
        Json::decode('foo');
    }
}

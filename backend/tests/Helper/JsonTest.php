<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use IntruderAlert\Helper\Json;
use IntruderAlert\Exception\AppException;

#[CoversClass(Json::class)]
#[UsesClass(AppException::class)]
class JsonTest extends AbstractTestCase
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

    public function testEncodeInvalid(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('JSON Error: Malformed UTF-8 characters, possibly incorrectly encoded');
        Json::encode("\xB1\x31");
    }

    public function testDecodeInvalid(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage('JSON Error: Syntax error');
        Json::decode('foo');
    }
}

<?php

declare(strict_types=1);

namespace IntruderAlert;

class Version
{
    /**
     * @var string $version Intruder Alert version
     */
    private static string $version = '1.21.0';

    /**
     * Returns version number
     */
    public static function get(): string
    {
        return self::$version;
    }
}

<?php

namespace IntruderAlert;

class Version
{
    /**
     * @var string $version Intruder Alert version
     */
    private static string $version = '1.14.1';

    /**
     * Returns version number
     */
    public static function get(): string
    {
        return self::$version;
    }
}

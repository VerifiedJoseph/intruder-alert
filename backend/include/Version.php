<?php

namespace IntruderAlert;

class Version
{
    /**
     * @var string $version Intruder Alert version
     */
    private static string $version = '1.19.7';

    /**
     * Returns version number
     */
    public static function get(): string
    {
        return self::$version;
    }
}

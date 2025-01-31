<?php

declare(strict_types=1);

namespace IntruderAlert\Helper;

use DateTime;
use DateTimeZone;

class Convert
{
    /**
     * Convert a date and time string from one timezone to another.
     * @param string $date Date string
     * @param string $source Source timezone
     * @param string $target Target timezone
     * @return string
     */
    public static function timezone(string $date, string $source, string $target): string
    {
        $dt = new DateTime($date, new DateTimeZone($source));
        $dt->setTimezone(new DateTimeZone($target));
        return $dt->format('Y-m-d H:i:s');
    }
}

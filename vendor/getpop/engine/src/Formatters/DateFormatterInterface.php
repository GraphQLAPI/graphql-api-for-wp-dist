<?php

declare (strict_types=1);
namespace PoP\Engine\Formatters;

interface DateFormatterInterface
{
    /**
     * Formatted date string or sum of Unix timestamp and timezone offset. False on failure.
     * @return string|int|bool
     */
    public function format(string $format, string $date);
}

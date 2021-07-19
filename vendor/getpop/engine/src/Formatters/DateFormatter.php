<?php

declare (strict_types=1);
namespace PoP\Engine\Formatters;

class DateFormatter implements \PoP\Engine\Formatters\DateFormatterInterface
{
    /**
     * @return string|int|bool
     */
    public function format(string $format, string $date)
    {
        return \date($format, \strtotime($date));
    }
}

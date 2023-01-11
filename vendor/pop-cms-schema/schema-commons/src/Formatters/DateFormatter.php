<?php

declare (strict_types=1);
namespace PoPCMSSchema\SchemaCommons\Formatters;

class DateFormatter implements \PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface
{
    /**
     * @return string|int|true
     * @param string $format
     * @param string $date
     */
    public function format($format, $date)
    {
        $time = \strtotime($date);
        if ($time === \false) {
            return \false;
        }
        return \date($format, $time);
    }
}

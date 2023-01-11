<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommonsWP\Formatters;

use PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface;

use function mysql2date;

class DateFormatter implements DateFormatterInterface
{
    /**
     * @return string|int|true
     * @param string $format
     * @param string $date
     */
    public function format($format, $date)
    {
        return mysql2date($format, $date);
    }
}

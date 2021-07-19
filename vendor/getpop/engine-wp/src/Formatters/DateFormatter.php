<?php

declare(strict_types=1);

namespace PoP\EngineWP\Formatters;

use PoP\Engine\Formatters\DateFormatterInterface;

class DateFormatter implements DateFormatterInterface
{
    /**
     * @return string|int|bool
     */
    public function format(string $format, string $date)
    {
        return mysql2date($format, $date);
    }
}

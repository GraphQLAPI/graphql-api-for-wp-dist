<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DataStructureFormatters;

use PoP\Root\Services\BasicServiceTrait;
abstract class AbstractDataStructureFormatter implements \PoP\ComponentModel\DataStructureFormatters\DataStructureFormatterInterface
{
    use BasicServiceTrait;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $data
     */
    public function getFormattedData($data) : array
    {
        return $data;
    }
}

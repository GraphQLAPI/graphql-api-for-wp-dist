<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DataStructureFormatters;

abstract class AbstractJSONDataStructureFormatter extends \PoP\ComponentModel\DataStructureFormatters\AbstractDataStructureFormatter
{
    public function getContentType() : string
    {
        return 'application/json';
    }
    /**
     * @param array<string,mixed> $data
     */
    public function getOutputContent(&$data) : string
    {
        return (string) \json_encode($data);
    }
}

<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FilterInputs;

interface FilterInputInterface
{
    /**
     * @param array<string,mixed> $query
     * @param mixed $value
     */
    public function filterDataloadQueryArgs(&$query, $value) : void;
}

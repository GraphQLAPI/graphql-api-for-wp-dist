<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DataStructure;

class DefaultDataStructureFormatter extends \PoP\ComponentModel\DataStructure\AbstractJSONDataStructureFormatter
{
    public function getName() : string
    {
        return 'default';
    }
}

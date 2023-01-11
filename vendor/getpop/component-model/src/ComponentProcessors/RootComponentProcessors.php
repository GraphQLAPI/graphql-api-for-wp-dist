<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

class RootComponentProcessors extends \PoP\ComponentModel\ComponentProcessors\AbstractComponentProcessor
{
    public const COMPONENT_EMPTY = 'empty';
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_EMPTY);
    }
}

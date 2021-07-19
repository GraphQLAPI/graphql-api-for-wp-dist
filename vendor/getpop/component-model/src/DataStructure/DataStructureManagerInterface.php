<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DataStructure;

interface DataStructureManagerInterface
{
    public function addDataStructureFormatter(\PoP\ComponentModel\DataStructure\DataStructureFormatterInterface $formatter) : void;
    public function setDefaultDataStructureFormatter(\PoP\ComponentModel\DataStructure\DataStructureFormatterInterface $formatter) : void;
    /**
     * @param string $name
     */
    public function getDataStructureFormatter($name = null) : \PoP\ComponentModel\DataStructure\DataStructureFormatterInterface;
}

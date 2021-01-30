<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DataStructure;

interface DataStructureManagerInterface
{
    public function add(\PoP\ComponentModel\DataStructure\DataStructureFormatterInterface $formatter) : void;
    public function getDataStructureFormatter(string $name = null) : \PoP\ComponentModel\DataStructure\DataStructureFormatterInterface;
}

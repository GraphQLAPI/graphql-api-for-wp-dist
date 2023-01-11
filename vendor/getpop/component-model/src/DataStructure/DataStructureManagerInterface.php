<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DataStructure;

use PoP\ComponentModel\DataStructureFormatters\DataStructureFormatterInterface;
interface DataStructureManagerInterface
{
    /**
     * @param \PoP\ComponentModel\DataStructureFormatters\DataStructureFormatterInterface $formatter
     */
    public function addDataStructureFormatter($formatter) : void;
    /**
     * @param \PoP\ComponentModel\DataStructureFormatters\DataStructureFormatterInterface $formatter
     */
    public function setDefaultDataStructureFormatter($formatter) : void;
    /**
     * @param string|null $name
     */
    public function getDataStructureFormatter($name = null) : DataStructureFormatterInterface;
}

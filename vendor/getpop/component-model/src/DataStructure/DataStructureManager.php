<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DataStructure;

use PoP\ComponentModel\DataStructure\DataStructureFormatterInterface;
use PoP\ComponentModel\State\ApplicationState;
class DataStructureManager implements \PoP\ComponentModel\DataStructure\DataStructureManagerInterface
{
    /**
     * @var array<string, DataStructureFormatterInterface>
     */
    public $formatters = [];
    /**
     * @var \PoP\ComponentModel\DataStructure\DataStructureFormatterInterface
     */
    protected $defaultFormatter;
    function __construct(\PoP\ComponentModel\DataStructure\DataStructureFormatterInterface $defaultFormatter)
    {
        $this->defaultFormatter = $defaultFormatter;
    }
    public function addDataStructureFormatter(\PoP\ComponentModel\DataStructure\DataStructureFormatterInterface $formatter) : void
    {
        $this->formatters[$formatter->getName()] = $formatter;
    }
    public function setDefaultDataStructureFormatter(\PoP\ComponentModel\DataStructure\DataStructureFormatterInterface $defaultFormatter) : void
    {
        $this->defaultFormatter = $defaultFormatter;
    }
    public function getDataStructureFormatter(string $name = null) : \PoP\ComponentModel\DataStructure\DataStructureFormatterInterface
    {
        // Return the formatter if it exists
        if ($name && isset($this->formatters[$name])) {
            return $this->formatters[$name];
        }
        // Return the one saved in the vars
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        $name = $vars['datastructure'];
        if ($name && isset($this->formatters[$name])) {
            return $this->formatters[$name];
        }
        return $this->defaultFormatter;
    }
}

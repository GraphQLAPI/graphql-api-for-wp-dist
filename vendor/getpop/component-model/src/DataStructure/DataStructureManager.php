<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DataStructure;

use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
class DataStructureManager implements \PoP\ComponentModel\DataStructure\DataStructureManagerInterface
{
    /**
     * @var array<string, DataStructureFormatterInterface>
     */
    public $formatters = [];
    public function add(\PoP\ComponentModel\DataStructure\DataStructureFormatterInterface $formatter) : void
    {
        $this->formatters[$formatter::getName()] = $formatter;
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
        // Return the default one
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        /**
         * @var DefaultDataStructureFormatter
         */
        $formatter = $instanceManager->getInstance(\PoP\ComponentModel\DataStructure\DefaultDataStructureFormatter::class);
        return $formatter;
    }
}

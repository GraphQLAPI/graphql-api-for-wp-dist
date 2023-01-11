<?php

declare (strict_types=1);
namespace PoP\ComponentModel\DataStructure;

use PoP\ComponentModel\DataStructureFormatters\DataStructureFormatterInterface;
use PoP\Root\App;
class DataStructureManager implements \PoP\ComponentModel\DataStructure\DataStructureManagerInterface
{
    /**
     * @var array<string,DataStructureFormatterInterface>
     */
    public $formatters = [];
    /**
     * @var \PoP\ComponentModel\DataStructureFormatters\DataStructureFormatterInterface
     */
    protected $defaultFormatter;
    public function __construct(DataStructureFormatterInterface $defaultFormatter)
    {
        $this->defaultFormatter = $defaultFormatter;
    }
    /**
     * @param \PoP\ComponentModel\DataStructureFormatters\DataStructureFormatterInterface $formatter
     */
    public function addDataStructureFormatter($formatter) : void
    {
        $this->formatters[$formatter->getName()] = $formatter;
    }
    /**
     * @param \PoP\ComponentModel\DataStructureFormatters\DataStructureFormatterInterface $defaultFormatter
     */
    public function setDefaultDataStructureFormatter($defaultFormatter) : void
    {
        $this->defaultFormatter = $defaultFormatter;
    }
    /**
     * @param string|null $name
     */
    public function getDataStructureFormatter($name = null) : DataStructureFormatterInterface
    {
        // Return the formatter if it exists
        if ($name && isset($this->formatters[$name])) {
            return $this->formatters[$name];
        }
        // Return the one saved in the vars
        $name = App::getState('datastructure');
        if ($name !== null && isset($this->formatters[$name])) {
            return $this->formatters[$name];
        }
        return $this->defaultFormatter;
    }
}

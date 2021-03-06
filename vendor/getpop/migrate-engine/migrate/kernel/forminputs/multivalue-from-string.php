<?php

namespace PoP\Engine;

use PoP\ComponentModel\GD_FormInput;
class GD_FormInput_MultiValueFromString extends \PoP\ComponentModel\GD_FormInput
{
    private $separator;
    public function __construct($params = array())
    {
        parent::__construct($params);
        $this->separator = $params['separator'] ?? \PoP\ComponentModel\Tokens\Param::VALUE_SEPARATOR;
    }
    public function getValue(?array $source = null)
    {
        $value = parent::getValue($source);
        // Only if it is not null process it
        if (!\is_null($value)) {
            return \array_map('trim', \explode($this->separator, $value));
        }
        return $value;
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\SchemaCommons\FormInputs;

use PoP\ComponentModel\FormInputs\FormInput;
use PoP\ComponentModel\Tokens\Param;
class MultiValueFromStringFormInput extends FormInput
{
    /**
     * @var string
     */
    private $separator;
    /**
     * @param array<string,mixed> $params
     * @param mixed $selected
     */
    public function __construct(string $name, $selected = null, array $params = [])
    {
        parent::__construct($name, $selected, $params);
        $this->separator = $params['separator'] ?? Param::VALUE_SEPARATOR;
    }
    /**
     * @param array<string,mixed>|null $source
     * @return mixed
     */
    public function getValue($source = null)
    {
        $value = parent::getValue($source);
        // Only if it is not null process it
        if ($value === null) {
            return $value;
        }
        return \array_map(\Closure::fromCallable('trim'), \explode($this->separator, $value));
    }
}

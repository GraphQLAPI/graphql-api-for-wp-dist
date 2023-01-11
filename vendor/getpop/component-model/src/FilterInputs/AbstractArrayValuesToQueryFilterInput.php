<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FilterInputs;

abstract class AbstractArrayValuesToQueryFilterInput extends \PoP\ComponentModel\FilterInputs\AbstractFilterInput
{
    /**
     * @param array<string,mixed> $query
     * @param mixed $value
     */
    public final function filterDataloadQueryArgs(&$query, $value) : void
    {
        /** @var mixed[] $value */
        $value = $this->getValue($value);
        $avoidSettingArrayValueIfEmpty = $this->avoidSettingArrayValueIfEmpty();
        foreach ($this->getValueToQueryArgKeys() as $valueKey => $queryKey) {
            if (\is_numeric($valueKey)) {
                $valueKey = $queryKey;
            }
            if ($avoidSettingArrayValueIfEmpty && empty($value[$valueKey] ?? null)) {
                continue;
            }
            $query[$queryKey] = $value[$valueKey] ?? null;
        }
    }
    /**
     * @return array<int|string,string>
     */
    protected abstract function getValueToQueryArgKeys() : array;
    /**
     * @return mixed[]
     * @param mixed[] $value
     */
    protected function getValue($value) : array
    {
        return $value;
    }
    protected function avoidSettingArrayValueIfEmpty() : bool
    {
        return \false;
    }
}

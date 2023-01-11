<?php

declare (strict_types=1);
namespace PoP\ComponentModel\QueryResolution;

use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;
interface FieldOrDirectiveDataAccessorInterface
{
    /**
     * This method can be called even before resolving the value,
     * as to find out if it was set (even if the value will,
     * upon retrieval, throw a `ValueResolutionPromiseException`)
     *
     * @see method `getValue` in this same interface
     * @return string[]
     */
    public function getProperties() : array;
    /**
     * This method can be called even before resolving the value,
     * as to find out if it was set (even if the value will,
     * upon retrieval, throw a `ValueResolutionPromiseException`)
     *
     * @see method `getValue` in this same interface
     * @param string $propertyName
     */
    public function hasValue($propertyName) : bool;
    /**
     * @throws AbstractValueResolutionPromiseException
     * @return mixed
     * @param string $propertyName
     */
    public function getValue($propertyName);
}

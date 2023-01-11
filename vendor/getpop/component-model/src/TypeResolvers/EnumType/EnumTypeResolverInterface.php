<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\LeafOutputTypeResolverInterface;
interface EnumTypeResolverInterface extends ConcreteTypeResolverInterface, DeprecatableInputTypeResolverInterface, LeafOutputTypeResolverInterface
{
    /**
     * The values in the enum
     *
     * @return string[]
     */
    public function getEnumValues() : array;
    /**
     * The “sensitive” values in the enum
     *
     * @return string[]
     */
    public function getSensitiveEnumValues() : array;
    /**
     * Description for a specific enum value
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue) : ?string;
    /**
     * Deprecation message for a specific enum value
     * @param string $enumValue
     */
    public function getEnumValueDeprecationMessage($enumValue) : ?string;
    /**
     * Consolidation of the enum values. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return string[]
     */
    public function getConsolidatedEnumValues() : array;
    /**
     * @return string[]
     */
    public function getConsolidatedAdminEnumValues() : array;
    /**
     * @param string $enumValue
     */
    public function getConsolidatedEnumValueDescription($enumValue) : ?string;
    /**
     * @param string $enumValue
     */
    public function getConsolidatedEnumValueDeprecationMessage($enumValue) : ?string;
    /**
     * @return array<string,mixed>
     * @param string $enumValue
     */
    public function getEnumValueSchemaDefinition($enumValue) : array;
    /**
     * @return array<string,array<string,mixed>>
     */
    public function getEnumSchemaDefinition() : array;
}

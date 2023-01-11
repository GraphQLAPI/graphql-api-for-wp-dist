<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
trait CheckDangerouslyNonSpecificScalarTypeFieldOrFieldDirectiveResolverTrait
{
    protected abstract function getDangerouslyNonSpecificScalarTypeScalarTypeResolver() : DangerouslyNonSpecificScalarTypeScalarTypeResolver;
    /**
     * `DangerouslyNonSpecificScalar` is a special scalar type which is not coerced or validated.
     * In particular, it does not need to validate if it is an array or not,
     * as according to the applied WrappingType.
     *
     * If disabled, then do not expose the field if either:
     *
     * 1. its type is `DangerouslyNonSpecificScalar`
     * 2. it has any mandatory argument of type `DangerouslyNonSpecificScalar`
     *
     * @param array<string,InputTypeResolverInterface> $consolidatedFieldArgNameTypeResolvers
     * @param array<string,int> $consolidatedFieldArgsTypeModifiers
     * @param \PoP\ComponentModel\TypeResolvers\TypeResolverInterface $fieldTypeResolver
     */
    protected function isDangerouslyNonSpecificScalarTypeFieldType($fieldTypeResolver, $consolidatedFieldArgNameTypeResolvers, $consolidatedFieldArgsTypeModifiers) : bool
    {
        // 1. its type is `DangerouslyNonSpecificScalar`
        if ($fieldTypeResolver === $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver()) {
            return \true;
        }
        // 2. it has any mandatory argument of type `DangerouslyNonSpecificScalar`
        if ($this->hasMandatoryDangerouslyNonSpecificScalarTypeInputType($consolidatedFieldArgNameTypeResolvers, $consolidatedFieldArgsTypeModifiers)) {
            return \true;
        }
        return \false;
    }
    /**
     * @param array<string,InputTypeResolverInterface> $consolidatedFieldOrDirectiveArgNameTypeResolvers
     * @param array<string,int> $consolidatedFieldOrDirectiveArgsTypeModifiers
     */
    protected function hasMandatoryDangerouslyNonSpecificScalarTypeInputType($consolidatedFieldOrDirectiveArgNameTypeResolvers, $consolidatedFieldOrDirectiveArgsTypeModifiers) : bool
    {
        $dangerouslyNonSpecificScalarTypeFieldOrDirectiveArgNameTypeResolvers = \array_filter($consolidatedFieldOrDirectiveArgNameTypeResolvers, function (InputTypeResolverInterface $inputTypeResolver) {
            return $inputTypeResolver === $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver();
        });
        foreach (\array_keys($dangerouslyNonSpecificScalarTypeFieldOrDirectiveArgNameTypeResolvers) as $fieldOrDirectiveArgName) {
            $consolidatedFieldOrDirectiveArgTypeModifiers = $consolidatedFieldOrDirectiveArgsTypeModifiers[$fieldOrDirectiveArgName];
            if ($consolidatedFieldOrDirectiveArgTypeModifiers & (SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::MANDATORY_BUT_NULLABLE)) {
                return \true;
            }
        }
        return \false;
    }
}

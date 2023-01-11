<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Hooks;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
/**
 * To be used together with:
 *
 * - RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait
 * - AbstractTransientObject
 */
abstract class AbstractRemoveIdentifiableObjectFieldsFromObjectTypeHookSet extends \PoP\ComponentModel\Hooks\AbstractRemoveFieldsFromObjectTypeHookSet
{
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface|\PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver
     * @param \PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface|\PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver
     * @param string $fieldName
     */
    protected function matchesCondition($objectTypeOrInterfaceTypeResolver, $objectTypeOrInterfaceTypeFieldResolver, $fieldName) : bool
    {
        return $fieldName === 'id' || $fieldName === 'globalID' || $fieldName === 'self' && $this->removeSelfField();
    }
    protected function removeSelfField() : bool
    {
        return \true;
    }
}

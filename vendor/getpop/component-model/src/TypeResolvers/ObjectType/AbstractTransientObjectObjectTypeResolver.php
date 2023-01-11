<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\ObjectModels\TransientObjectInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait;
abstract class AbstractTransientObjectObjectTypeResolver extends AbstractObjectTypeResolver
{
    use RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait;
    /**
     * @return string|int|null
     * @param object $object
     */
    public final function getID($object)
    {
        /** @var TransientObjectInterface */
        $transientObject = $object;
        return $transientObject->getID();
    }
    /**
     * Remove the IdentifiableObject interface
     *
     * @param InterfaceTypeFieldResolverInterface[] $interfaceTypeFieldResolvers
     * @return InterfaceTypeFieldResolverInterface[]
     */
    protected final function consolidateAllImplementedInterfaceTypeFieldResolvers($interfaceTypeFieldResolvers) : array
    {
        return $this->removeIdentifiableObjectInterfaceTypeFieldResolver(parent::consolidateAllImplementedInterfaceTypeFieldResolvers($interfaceTypeFieldResolvers));
    }
}

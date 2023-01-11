<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\IdentifiableObjectInterfaceTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class IdentifiableObjectObjectTypeFieldResolver extends \PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoP\ComponentModel\FieldResolvers\InterfaceType\IdentifiableObjectInterfaceTypeFieldResolver|null
     */
    private $identifiableObjectInterfaceTypeFieldResolver;
    /**
     * @param \PoP\ComponentModel\FieldResolvers\InterfaceType\IdentifiableObjectInterfaceTypeFieldResolver $identifiableObjectInterfaceTypeFieldResolver
     */
    public final function setIdentifiableObjectInterfaceTypeFieldResolver($identifiableObjectInterfaceTypeFieldResolver) : void
    {
        $this->identifiableObjectInterfaceTypeFieldResolver = $identifiableObjectInterfaceTypeFieldResolver;
    }
    protected final function getIdentifiableObjectInterfaceTypeFieldResolver() : IdentifiableObjectInterfaceTypeFieldResolver
    {
        /** @var IdentifiableObjectInterfaceTypeFieldResolver */
        return $this->identifiableObjectInterfaceTypeFieldResolver = $this->identifiableObjectInterfaceTypeFieldResolver ?? $this->instanceManager->getInstance(IdentifiableObjectInterfaceTypeFieldResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [AbstractObjectTypeResolver::class];
    }
    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers() : array
    {
        return [$this->getIdentifiableObjectInterfaceTypeFieldResolver()];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['id', 'globalID'];
    }
    /**
     * @return mixed
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        switch ($fieldDataAccessor->getFieldName()) {
            case 'id':
                return $objectTypeResolver->getID($object);
            case 'globalID':
                return \base64_encode(\sprintf('%s:%s', $objectTypeResolver->getNamespacedTypeName(), $objectTypeResolver->getID($object)));
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}

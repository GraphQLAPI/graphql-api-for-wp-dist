<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\Extensions;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use GraphQLByPoP\GraphQLServer\ObjectModels\Directive;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveKindEnumTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class DirectiveSchemaObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveKindEnumTypeResolver|null
     */
    private $directiveKindEnumTypeResolver;
    /**
     * @var \PoP\ComponentModel\Registries\DirectiveRegistryInterface|null
     */
    private $directiveRegistry;
    /**
     * @param \GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveKindEnumTypeResolver $directiveKindEnumTypeResolver
     */
    public final function setDirectiveKindEnumTypeResolver($directiveKindEnumTypeResolver) : void
    {
        $this->directiveKindEnumTypeResolver = $directiveKindEnumTypeResolver;
    }
    protected final function getDirectiveKindEnumTypeResolver() : DirectiveKindEnumTypeResolver
    {
        /** @var DirectiveKindEnumTypeResolver */
        return $this->directiveKindEnumTypeResolver = $this->directiveKindEnumTypeResolver ?? $this->instanceManager->getInstance(DirectiveKindEnumTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\Registries\DirectiveRegistryInterface $directiveRegistry
     */
    public final function setDirectiveRegistry($directiveRegistry) : void
    {
        $this->directiveRegistry = $directiveRegistry;
    }
    protected final function getDirectiveRegistry() : DirectiveRegistryInterface
    {
        /** @var DirectiveRegistryInterface */
        return $this->directiveRegistry = $this->directiveRegistry ?? $this->instanceManager->getInstance(DirectiveRegistryInterface::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [DirectiveObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['kind'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function skipExposingFieldInSchema($objectTypeResolver, $fieldName) : bool
    {
        switch ($fieldName) {
            case 'kind':
                return \true;
            default:
                return parent::skipExposingFieldInSchema($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        switch ($fieldName) {
            case 'kind':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'kind':
                return $this->__('The directive type (custom property)', 'graphql-server');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
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
        /** @var Directive */
        $directive = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'kind':
                return $directive->getKind();
            default:
                return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'kind':
                return $this->getDirectiveKindEnumTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
}

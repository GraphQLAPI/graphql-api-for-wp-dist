<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use GraphQLByPoP\GraphQLServer\ObjectModels\InputValue;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueExtensionsObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
class InputValueObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver|null
     */
    private $typeObjectTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueExtensionsObjectTypeResolver|null
     */
    private $inputValueExtensionsObjectTypeResolver;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver
     */
    public final function setStringScalarTypeResolver($stringScalarTypeResolver) : void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected final function getStringScalarTypeResolver() : StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver = $this->stringScalarTypeResolver ?? $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    /**
     * @param \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver $typeObjectTypeResolver
     */
    public final function setTypeObjectTypeResolver($typeObjectTypeResolver) : void
    {
        $this->typeObjectTypeResolver = $typeObjectTypeResolver;
    }
    protected final function getTypeObjectTypeResolver() : TypeObjectTypeResolver
    {
        /** @var TypeObjectTypeResolver */
        return $this->typeObjectTypeResolver = $this->typeObjectTypeResolver ?? $this->instanceManager->getInstance(TypeObjectTypeResolver::class);
    }
    /**
     * @param \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueExtensionsObjectTypeResolver $inputValueExtensionsObjectTypeResolver
     */
    public final function setInputValueExtensionsObjectTypeResolver($inputValueExtensionsObjectTypeResolver) : void
    {
        $this->inputValueExtensionsObjectTypeResolver = $inputValueExtensionsObjectTypeResolver;
    }
    protected final function getInputValueExtensionsObjectTypeResolver() : InputValueExtensionsObjectTypeResolver
    {
        /** @var InputValueExtensionsObjectTypeResolver */
        return $this->inputValueExtensionsObjectTypeResolver = $this->inputValueExtensionsObjectTypeResolver ?? $this->instanceManager->getInstance(InputValueExtensionsObjectTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [InputValueObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['name', 'description', 'type', 'defaultValue', 'extensions'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'name':
                return $this->getStringScalarTypeResolver();
            case 'description':
                return $this->getStringScalarTypeResolver();
            case 'defaultValue':
                return $this->getStringScalarTypeResolver();
            case 'type':
                return $this->getTypeObjectTypeResolver();
            case 'extensions':
                return $this->getInputValueExtensionsObjectTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        switch ($fieldName) {
            case 'name':
            case 'type':
            case 'extensions':
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
            case 'name':
                return $this->__('Input value\'s name as defined by the GraphQL spec', 'graphql-server');
            case 'description':
                return $this->__('Input value\'s description', 'graphql-server');
            case 'type':
                return $this->__('Type of the input value', 'graphql-server');
            case 'defaultValue':
                return $this->__('Default value of the input value', 'graphql-server');
            case 'extensions':
                return $this->__('Extensions (custom metadata) added to the input (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)', 'graphql-server');
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
        /** @var InputValue */
        $inputValue = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'name':
                return $inputValue->getName();
            case 'description':
                return $inputValue->getDescription();
            case 'type':
                return $inputValue->getTypeID();
            case 'defaultValue':
                return $inputValue->getDefaultValue();
            case 'extensions':
                return $inputValue->getExtensions()->getID();
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}

<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use GraphQLByPoP\GraphQLServer\ObjectModels\Field;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\FieldExtensionsObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\FieldObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
class FieldObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver|null
     */
    private $booleanScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\FieldExtensionsObjectTypeResolver|null
     */
    private $fieldExtensionsObjectTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueObjectTypeResolver|null
     */
    private $inputValueObjectTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver|null
     */
    private $typeObjectTypeResolver;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver $booleanScalarTypeResolver
     */
    public final function setBooleanScalarTypeResolver($booleanScalarTypeResolver) : void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    protected final function getBooleanScalarTypeResolver() : BooleanScalarTypeResolver
    {
        /** @var BooleanScalarTypeResolver */
        return $this->booleanScalarTypeResolver = $this->booleanScalarTypeResolver ?? $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
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
     * @param \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\FieldExtensionsObjectTypeResolver $fieldExtensionsObjectTypeResolver
     */
    public final function setFieldExtensionsObjectTypeResolver($fieldExtensionsObjectTypeResolver) : void
    {
        $this->fieldExtensionsObjectTypeResolver = $fieldExtensionsObjectTypeResolver;
    }
    protected final function getFieldExtensionsObjectTypeResolver() : FieldExtensionsObjectTypeResolver
    {
        /** @var FieldExtensionsObjectTypeResolver */
        return $this->fieldExtensionsObjectTypeResolver = $this->fieldExtensionsObjectTypeResolver ?? $this->instanceManager->getInstance(FieldExtensionsObjectTypeResolver::class);
    }
    /**
     * @param \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueObjectTypeResolver $inputValueObjectTypeResolver
     */
    public final function setInputValueObjectTypeResolver($inputValueObjectTypeResolver) : void
    {
        $this->inputValueObjectTypeResolver = $inputValueObjectTypeResolver;
    }
    protected final function getInputValueObjectTypeResolver() : InputValueObjectTypeResolver
    {
        /** @var InputValueObjectTypeResolver */
        return $this->inputValueObjectTypeResolver = $this->inputValueObjectTypeResolver ?? $this->instanceManager->getInstance(InputValueObjectTypeResolver::class);
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
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [FieldObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['name', 'description', 'args', 'type', 'isDeprecated', 'deprecationReason', 'extensions'];
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
            case 'isDeprecated':
                return $this->getBooleanScalarTypeResolver();
            case 'deprecationReason':
                return $this->getStringScalarTypeResolver();
            case 'extensions':
                return $this->getFieldExtensionsObjectTypeResolver();
            case 'args':
                return $this->getInputValueObjectTypeResolver();
            case 'type':
                return $this->getTypeObjectTypeResolver();
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
            case 'isDeprecated':
            case 'extensions':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'args':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
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
                return $this->__('Field\'s name', 'graphql-server');
            case 'description':
                return $this->__('Field\'s description', 'graphql-server');
            case 'args':
                return $this->__('Field arguments', 'graphql-server');
            case 'type':
                return $this->__('Type to which the field belongs', 'graphql-server');
            case 'isDeprecated':
                return $this->__('Is the field deprecated?', 'graphql-server');
            case 'deprecationReason':
                return $this->__('Why was the field deprecated?', 'graphql-server');
            case 'extensions':
                return $this->__('Extensions (custom metadata) added to the field (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)', 'graphql-server');
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
        /** @var Field */
        $fieldObject = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'name':
                return $fieldObject->getName();
            case 'description':
                return $fieldObject->getDescription();
            case 'args':
                return $fieldObject->getArgIDs();
            case 'type':
                return $fieldObject->getTypeID();
            case 'isDeprecated':
                return $fieldObject->isDeprecated();
            case 'deprecationReason':
                return $fieldObject->getDeprecationMessage();
            case 'extensions':
                return $fieldObject->getExtensions()->getID();
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}

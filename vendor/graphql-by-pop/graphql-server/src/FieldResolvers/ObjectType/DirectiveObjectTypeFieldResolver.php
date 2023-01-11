<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use GraphQLByPoP\GraphQLServer\ObjectModels\Directive;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveLocationEnumTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveExtensionsObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
class DirectiveObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver|null
     */
    private $booleanScalarTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueObjectTypeResolver|null
     */
    private $inputValueObjectTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveLocationEnumTypeResolver|null
     */
    private $directiveLocationEnumTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveExtensionsObjectTypeResolver|null
     */
    private $directiveExtensionsObjectTypeResolver;
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
     * @param \GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveLocationEnumTypeResolver $directiveLocationEnumTypeResolver
     */
    public final function setDirectiveLocationEnumTypeResolver($directiveLocationEnumTypeResolver) : void
    {
        $this->directiveLocationEnumTypeResolver = $directiveLocationEnumTypeResolver;
    }
    protected final function getDirectiveLocationEnumTypeResolver() : DirectiveLocationEnumTypeResolver
    {
        /** @var DirectiveLocationEnumTypeResolver */
        return $this->directiveLocationEnumTypeResolver = $this->directiveLocationEnumTypeResolver ?? $this->instanceManager->getInstance(DirectiveLocationEnumTypeResolver::class);
    }
    /**
     * @param \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveExtensionsObjectTypeResolver $directiveExtensionsObjectTypeResolver
     */
    public final function setDirectiveExtensionsObjectTypeResolver($directiveExtensionsObjectTypeResolver) : void
    {
        $this->directiveExtensionsObjectTypeResolver = $directiveExtensionsObjectTypeResolver;
    }
    protected final function getDirectiveExtensionsObjectTypeResolver() : DirectiveExtensionsObjectTypeResolver
    {
        /** @var DirectiveExtensionsObjectTypeResolver */
        return $this->directiveExtensionsObjectTypeResolver = $this->directiveExtensionsObjectTypeResolver ?? $this->instanceManager->getInstance(DirectiveExtensionsObjectTypeResolver::class);
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
        return ['name', 'description', 'args', 'locations', 'isRepeatable', 'extensions'];
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
            case 'isRepeatable':
                return $this->getBooleanScalarTypeResolver();
            case 'args':
                return $this->getInputValueObjectTypeResolver();
            case 'locations':
                return $this->getDirectiveLocationEnumTypeResolver();
            case 'extensions':
                return $this->getDirectiveExtensionsObjectTypeResolver();
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
            case 'isRepeatable':
            case 'extensions':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'locations':
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
                return $this->__('Directive\'s name', 'graphql-server');
            case 'description':
                return $this->__('Directive\'s description', 'graphql-server');
            case 'args':
                return $this->__('Directive\'s arguments', 'graphql-server');
            case 'locations':
                return $this->__('The locations where the directive may be placed', 'graphql-server');
            case 'isRepeatable':
                return $this->__('Can the directive be executed more than once in the same field?', 'graphql-server');
            case 'extensions':
                return $this->__('Extensions (custom metadata) added to the directive (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)', 'graphql-server');
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
            case 'name':
                return $directive->getName();
            case 'description':
                return $directive->getDescription();
            case 'args':
                return $directive->getArgIDs();
            case 'locations':
                return $directive->getLocations();
            case 'isRepeatable':
                return $directive->isRepeatable();
            case 'extensions':
                return $directive->getExtensions()->getID();
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}

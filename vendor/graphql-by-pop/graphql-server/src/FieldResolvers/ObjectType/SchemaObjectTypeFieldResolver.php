<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaExtensionsObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
class SchemaObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver|null
     */
    private $typeObjectTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveObjectTypeResolver|null
     */
    private $directiveObjectTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaExtensionsObjectTypeResolver|null
     */
    private $schemaExtensionsObjectTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
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
     * @param \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveObjectTypeResolver $directiveObjectTypeResolver
     */
    public final function setDirectiveObjectTypeResolver($directiveObjectTypeResolver) : void
    {
        $this->directiveObjectTypeResolver = $directiveObjectTypeResolver;
    }
    protected final function getDirectiveObjectTypeResolver() : DirectiveObjectTypeResolver
    {
        /** @var DirectiveObjectTypeResolver */
        return $this->directiveObjectTypeResolver = $this->directiveObjectTypeResolver ?? $this->instanceManager->getInstance(DirectiveObjectTypeResolver::class);
    }
    /**
     * @param \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaExtensionsObjectTypeResolver $schemaExtensionsObjectTypeResolver
     */
    public final function setSchemaExtensionsObjectTypeResolver($schemaExtensionsObjectTypeResolver) : void
    {
        $this->schemaExtensionsObjectTypeResolver = $schemaExtensionsObjectTypeResolver;
    }
    protected final function getSchemaExtensionsObjectTypeResolver() : SchemaExtensionsObjectTypeResolver
    {
        /** @var SchemaExtensionsObjectTypeResolver */
        return $this->schemaExtensionsObjectTypeResolver = $this->schemaExtensionsObjectTypeResolver ?? $this->instanceManager->getInstance(SchemaExtensionsObjectTypeResolver::class);
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
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [SchemaObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['queryType', 'mutationType', 'subscriptionType', 'types', 'directives', 'type', 'extensions'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        switch ($fieldName) {
            case 'queryType':
            case 'extensions':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'types':
            case 'directives':
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
            case 'queryType':
                return $this->__('The type, accessible from the root, that resolves queries', 'graphql-server');
            case 'mutationType':
                return $this->__('The type, accessible from the root, that resolves mutations', 'graphql-server');
            case 'subscriptionType':
                return $this->__('The type, accessible from the root, that resolves subscriptions', 'graphql-server');
            case 'types':
                return $this->__('All types registered in the data graph', 'graphql-server');
            case 'directives':
                return $this->__('All directives registered in the data graph', 'graphql-server');
            case 'type':
                return $this->__('Obtain a specific type from the schema', 'graphql-server');
            case 'extensions':
                return $this->__('Extensions (custom metadata) added to the GraphQL schema', 'graphql-server');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        switch ($fieldName) {
            case 'type':
                return ['name' => $this->getStringScalarTypeResolver()];
            default:
                return parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['type' => 'name']:
                return $this->__('The name of the type', 'graphql-server');
            default:
                return parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['type' => 'name']:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
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
        /** @var Schema */
        $schema = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'queryType':
                return $schema->getQueryRootObjectTypeID();
            case 'mutationType':
                return $schema->getMutationRootObjectTypeID();
            case 'subscriptionType':
                return $schema->getSubscriptionRootObjectTypeID();
            case 'types':
                return $schema->getTypeIDs();
            case 'directives':
                return $schema->getDirectiveIDs();
            case 'type':
                return $schema->getTypeID($fieldDataAccessor->getValue('name'));
            case 'extensions':
                return $schema->getExtensions()->getID();
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
            case 'queryType':
            case 'mutationType':
            case 'subscriptionType':
            case 'types':
            case 'type':
                return $this->getTypeObjectTypeResolver();
            case 'directives':
                return $this->getDirectiveObjectTypeResolver();
            case 'extensions':
                return $this->getSchemaExtensionsObjectTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
}

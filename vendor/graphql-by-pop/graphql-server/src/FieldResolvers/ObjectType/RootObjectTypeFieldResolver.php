<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaTypeDataLoader;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\Root\App;
class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver|null
     */
    private $schemaObjectTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver|null
     */
    private $typeObjectTypeResolver;
    /**
     * @var \GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaTypeDataLoader|null
     */
    private $schemaTypeDataLoader;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @param \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver $schemaObjectTypeResolver
     */
    public final function setSchemaObjectTypeResolver($schemaObjectTypeResolver) : void
    {
        $this->schemaObjectTypeResolver = $schemaObjectTypeResolver;
    }
    protected final function getSchemaObjectTypeResolver() : SchemaObjectTypeResolver
    {
        /** @var SchemaObjectTypeResolver */
        return $this->schemaObjectTypeResolver = $this->schemaObjectTypeResolver ?? $this->instanceManager->getInstance(SchemaObjectTypeResolver::class);
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
     * @param \GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\SchemaTypeDataLoader $schemaTypeDataLoader
     */
    public final function setSchemaTypeDataLoader($schemaTypeDataLoader) : void
    {
        $this->schemaTypeDataLoader = $schemaTypeDataLoader;
    }
    protected final function getSchemaTypeDataLoader() : SchemaTypeDataLoader
    {
        /** @var SchemaTypeDataLoader */
        return $this->schemaTypeDataLoader = $this->schemaTypeDataLoader ?? $this->instanceManager->getInstance(SchemaTypeDataLoader::class);
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
        return [RootObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        if (!App::getState('graphql-introspection-enabled')) {
            return [];
        }
        return ['__schema', '__type'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        switch ($fieldName) {
            case '__schema':
            case '__type':
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
            case '__schema':
                return $this->__('The GraphQL schema, exposing what fields can be queried', 'graphql-server');
            case '__type':
                return $this->__('Obtain a specific type from the schema', 'graphql-server');
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
            case '__type':
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
            case ['__type' => 'name']:
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
            case ['__type' => 'name']:
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
        $root = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case '__schema':
                return 'schema';
            case '__type':
                // Get an instance of the schema and then execute function `getType` there
                $schemaID = $objectTypeResolver->resolveValue($object, new LeafField('__schema', null, [], [], $fieldDataAccessor->getField()->getLocation()), $objectTypeFieldResolutionFeedbackStore);
                if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    return $schemaID;
                }
                // Obtain the instance of the schema
                /** @var Schema[] */
                $schemaInstances = $this->getSchemaTypeDataLoader()->getObjects([$schemaID]);
                $schema = $schemaInstances[0];
                return $schema->getTypeID($fieldDataAccessor->getValue('name'));
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case '__schema':
                return $this->getSchemaObjectTypeResolver();
            case '__type':
                return $this->getTypeObjectTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
}

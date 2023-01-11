<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\ListWrappingType;
use GraphQLByPoP\GraphQLServer\ObjectModels\NonNullWrappingType;
use GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinitionReferenceObjectInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\WrappingTypeInterface;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use GraphQLByPoP\GraphQLServer\Syntax\GraphQLSyntaxServiceInterface;
use PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
class WrappingTypeOrSchemaDefinitionReferenceTypeDataLoader extends AbstractObjectTypeDataLoader
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface|null
     */
    private $schemaDefinitionReferenceRegistry;
    /**
     * @var \GraphQLByPoP\GraphQLServer\Syntax\GraphQLSyntaxServiceInterface|null
     */
    private $graphQLSyntaxService;
    /**
     * @var \PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface|null
     */
    private $objectDictionary;
    /**
     * @param \GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry
     */
    public final function setSchemaDefinitionReferenceRegistry($schemaDefinitionReferenceRegistry) : void
    {
        $this->schemaDefinitionReferenceRegistry = $schemaDefinitionReferenceRegistry;
    }
    protected final function getSchemaDefinitionReferenceRegistry() : SchemaDefinitionReferenceRegistryInterface
    {
        /** @var SchemaDefinitionReferenceRegistryInterface */
        return $this->schemaDefinitionReferenceRegistry = $this->schemaDefinitionReferenceRegistry ?? $this->instanceManager->getInstance(SchemaDefinitionReferenceRegistryInterface::class);
    }
    /**
     * @param \GraphQLByPoP\GraphQLServer\Syntax\GraphQLSyntaxServiceInterface $graphQLSyntaxService
     */
    public final function setGraphQLSyntaxService($graphQLSyntaxService) : void
    {
        $this->graphQLSyntaxService = $graphQLSyntaxService;
    }
    protected final function getGraphQLSyntaxService() : GraphQLSyntaxServiceInterface
    {
        /** @var GraphQLSyntaxServiceInterface */
        return $this->graphQLSyntaxService = $this->graphQLSyntaxService ?? $this->instanceManager->getInstance(GraphQLSyntaxServiceInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface $objectDictionary
     */
    public final function setObjectDictionary($objectDictionary) : void
    {
        $this->objectDictionary = $objectDictionary;
    }
    protected final function getObjectDictionary() : ObjectDictionaryInterface
    {
        /** @var ObjectDictionaryInterface */
        return $this->objectDictionary = $this->objectDictionary ?? $this->instanceManager->getInstance(ObjectDictionaryInterface::class);
    }
    /**
     * The IDs can contain GraphQL's type wrappers, such as `[String]!`
     *
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects($ids) : array
    {
        /** @var string[] $ids */
        return \array_map(\Closure::fromCallable([$this, 'getWrappingTypeOrSchemaDefinitionReferenceObject']), $ids);
    }
    /**
     * @return \GraphQLByPoP\GraphQLServer\ObjectModels\WrappingTypeInterface|\GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinitionReferenceObjectInterface|null
     * @param string $typeID
     */
    protected function getWrappingTypeOrSchemaDefinitionReferenceObject($typeID)
    {
        // Check if the type is non-null or an array
        $isNonNullWrappingType = $this->getGraphQLSyntaxService()->isNonNullWrappingType($typeID);
        if ($isNonNullWrappingType || $this->getGraphQLSyntaxService()->isListWrappingType($typeID)) {
            // Store the single WrappingType instance in a dictionary
            $objectTypeResolverClass = \get_class();
            if ($this->getObjectDictionary()->has($objectTypeResolverClass, $typeID)) {
                return $this->getObjectDictionary()->get($objectTypeResolverClass, $typeID);
            }
            $wrappingType = null;
            if ($isNonNullWrappingType) {
                /** @var TypeInterface */
                $wrappedType = $this->getWrappingTypeOrSchemaDefinitionReferenceObject($this->getGraphQLSyntaxService()->extractWrappedTypeFromNonNullWrappingType($typeID));
                $wrappingType = new NonNullWrappingType($wrappedType);
            } else {
                /** @var TypeInterface */
                $wrappedType = $this->getWrappingTypeOrSchemaDefinitionReferenceObject($this->getGraphQLSyntaxService()->extractWrappedTypeFromListWrappingType($typeID));
                $wrappingType = new ListWrappingType($wrappedType);
            }
            $this->getObjectDictionary()->set($objectTypeResolverClass, $typeID, $wrappingType);
            return $wrappingType;
        }
        return $this->getSchemaDefinitionReferenceRegistry()->getSchemaDefinitionReferenceObject($typeID);
    }
}

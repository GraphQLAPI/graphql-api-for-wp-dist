<?php

declare (strict_types=1);
namespace PoPAPI\API\Schema;

use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\Engine\Cache\CacheUtils;
use PoP\Engine\Schema\SchemaDefinitionService as UpstreamSchemaDefinitionService;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;
use PoP\Root\Exception\ImpossibleToHappenException;
use PoPAPI\API\Cache\CacheTypes;
use PoPAPI\API\Module;
use PoPAPI\API\ModuleConfiguration;
use PoPAPI\API\ObjectModels\SchemaDefinition\DirectiveSchemaDefinitionProvider;
use PoPAPI\API\ObjectModels\SchemaDefinition\EnumTypeSchemaDefinitionProvider;
use PoPAPI\API\ObjectModels\SchemaDefinition\InputObjectTypeSchemaDefinitionProvider;
use PoPAPI\API\ObjectModels\SchemaDefinition\InterfaceTypeSchemaDefinitionProvider;
use PoPAPI\API\ObjectModels\SchemaDefinition\ObjectTypeSchemaDefinitionProvider;
use PoPAPI\API\ObjectModels\SchemaDefinition\RootObjectTypeSchemaDefinitionProvider;
use PoPAPI\API\ObjectModels\SchemaDefinition\ScalarTypeSchemaDefinitionProvider;
use PoPAPI\API\ObjectModels\SchemaDefinition\TypeSchemaDefinitionProviderInterface;
use PoPAPI\API\ObjectModels\SchemaDefinition\UnionTypeSchemaDefinitionProvider;
use PoPAPI\API\PersistedQueries\PersistedFragmentManagerInterface;
use PoPAPI\API\PersistedQueries\PersistedQueryManagerInterface;
class SchemaDefinitionService extends UpstreamSchemaDefinitionService implements \PoPAPI\API\Schema\SchemaDefinitionServiceInterface
{
    /**
     * Starting from the Root TypeResolver, iterate and get the
     * SchemaDefinition for all TypeResolvers and DirectiveResolvers
     * accessed in the schema
     *
     * @var array<class-string<TypeResolverInterface|FieldDirectiveResolverInterface>>
     */
    private $processedTypeAndFieldDirectiveResolverClasses = [];
    /** @var array<TypeResolverInterface|FieldDirectiveResolverInterface> */
    private $pendingTypeOrFieldDirectiveResolvers = [];
    /** @var array<string,RelationalTypeResolverInterface> Key: directive resolver class, Value: The Type Resolver Class which loads the directive */
    private $accessedFieldDirectiveResolverClassRelationalTypeResolvers = [];
    /** @var array<string,ObjectTypeResolverInterface[]> Key: InterfaceType name, Value: List of ObjectType resolvers implementing the interface */
    private $accessedInterfaceTypeNameObjectTypeResolvers = [];
    /**
     * @var \PoP\ComponentModel\Cache\PersistentCacheInterface|null
     */
    private $persistentCache;
    /**
     * @var \PoPAPI\API\PersistedQueries\PersistedFragmentManagerInterface|null
     */
    private $persistedFragmentManager;
    /**
     * @var \PoPAPI\API\PersistedQueries\PersistedQueryManagerInterface|null
     */
    private $persistedQueryManager;
    /**
     * Cannot autowire with "#[Required]" because its calling `getNamespace`
     * on services.yaml produces an exception of PHP properties not initialized
     * in its depended services.
     * @param \PoP\ComponentModel\Cache\PersistentCacheInterface $persistentCache
     */
    public final function setPersistentCache($persistentCache) : void
    {
        $this->persistentCache = $persistentCache;
    }
    public final function getPersistentCache() : PersistentCacheInterface
    {
        /** @var PersistentCacheInterface */
        return $this->persistentCache = $this->persistentCache ?? $this->instanceManager->getInstance(PersistentCacheInterface::class);
    }
    /**
     * @param \PoPAPI\API\PersistedQueries\PersistedFragmentManagerInterface $persistedFragmentManager
     */
    public final function setPersistedFragmentManager($persistedFragmentManager) : void
    {
        $this->persistedFragmentManager = $persistedFragmentManager;
    }
    protected final function getPersistedFragmentManager() : PersistedFragmentManagerInterface
    {
        /** @var PersistedFragmentManagerInterface */
        return $this->persistedFragmentManager = $this->persistedFragmentManager ?? $this->instanceManager->getInstance(PersistedFragmentManagerInterface::class);
    }
    /**
     * @param \PoPAPI\API\PersistedQueries\PersistedQueryManagerInterface $persistedQueryManager
     */
    public final function setPersistedQueryManager($persistedQueryManager) : void
    {
        $this->persistedQueryManager = $persistedQueryManager;
    }
    protected final function getPersistedQueryManager() : PersistedQueryManagerInterface
    {
        /** @var PersistedQueryManagerInterface */
        return $this->persistedQueryManager = $this->persistedQueryManager ?? $this->instanceManager->getInstance(PersistedQueryManagerInterface::class);
    }
    /**
     * @return array<string,mixed>
     */
    public function &getFullSchemaDefinition() : array
    {
        $schemaDefinition = null;
        // Attempt to retrieve from the cache, if enabled
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($useCache = $moduleConfiguration->useSchemaDefinitionCache()) {
            $persistentCache = $this->getPersistentCache();
            // Use different caches for the normal and namespaced schemas, or
            // it throws exception if switching without deleting the cache (eg: when passing ?use_namespace=1)
            $cacheType = CacheTypes::FULLSCHEMA_DEFINITION;
            $cacheKeyElements = CacheUtils::getSchemaCacheKeyElements();
            // For the persistentCache, use a hash to remove invalid characters (such as "()")
            $cacheKey = \hash('md5', (string) \json_encode($cacheKeyElements));
            if ($persistentCache->hasCache($cacheKey, $cacheType)) {
                $schemaDefinition = $persistentCache->getCache($cacheKey, $cacheType);
            }
        }
        if ($schemaDefinition === null) {
            $schemaDefinition = [\PoPAPI\API\Schema\SchemaDefinition::QUERY_TYPE => $this->getSchemaRootObjectTypeResolver()->getMaybeNamespacedTypeName(), \PoPAPI\API\Schema\SchemaDefinition::TYPES => []];
            $this->processedTypeAndFieldDirectiveResolverClasses = [];
            $this->accessedFieldDirectiveResolverClassRelationalTypeResolvers = [];
            $this->accessedInterfaceTypeNameObjectTypeResolvers = [];
            $this->pendingTypeOrFieldDirectiveResolvers = [$this->getSchemaRootObjectTypeResolver()];
            while (!empty($this->pendingTypeOrFieldDirectiveResolvers)) {
                $typeOrFieldDirectiveResolver = \array_pop($this->pendingTypeOrFieldDirectiveResolvers);
                $this->processedTypeAndFieldDirectiveResolverClasses[] = \get_class($typeOrFieldDirectiveResolver);
                if ($typeOrFieldDirectiveResolver instanceof TypeResolverInterface) {
                    /** @var TypeResolverInterface */
                    $typeResolver = $typeOrFieldDirectiveResolver;
                    $this->addTypeSchemaDefinition($typeResolver, $schemaDefinition);
                } else {
                    /** @var FieldDirectiveResolverInterface */
                    $directiveResolver = $typeOrFieldDirectiveResolver;
                    $this->addDirectiveSchemaDefinition($directiveResolver, $schemaDefinition);
                }
            }
            /**
             * Inject this ObjectTypeResolver into the POSSIBLE_TYPES from
             * its implemented InterfaceTypes.
             *
             * Watch out! This logic is implemented like this,
             * instead of retrieving them from the typeRegistry already
             * within InterfaceTypeSchemaDefinitionProvider,
             * because types which are not registered in the schema
             * (such as QueryRoot with nested mutations enabled)
             * must not be processed, yet they are still in typeRegistry
             */
            foreach ($this->accessedInterfaceTypeNameObjectTypeResolvers as $interfaceTypeName => $objectTypeResolvers) {
                $interfaceTypeSchemaDefinition =& $schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::INTERFACE][$interfaceTypeName];
                foreach ($objectTypeResolvers as $objectTypeResolver) {
                    $objectTypeName = $objectTypeResolver->getMaybeNamespacedTypeName();
                    $objectTypeSchemaDefinition = [\PoPAPI\API\Schema\SchemaDefinition::TYPE_RESOLVER => $objectTypeResolver];
                    \PoPAPI\API\Schema\SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($objectTypeSchemaDefinition);
                    $interfaceTypeSchemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::POSSIBLE_TYPES][$objectTypeName] = $objectTypeSchemaDefinition;
                }
            }
            // Add the Fragment Catalogue
            $schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::PERSISTED_FRAGMENTS] = $this->getPersistedFragmentManager()->getPersistedFragmentsForSchema();
            // Add the Query Catalogue
            $schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::PERSISTED_QUERIES] = $this->getPersistedQueryManager()->getPersistedQueriesForSchema();
            // Schema extensions
            $schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::EXTENSIONS] = $this->getSchemaExtensions();
            // Sort the elements in the schema alphabetically
            if ($moduleConfiguration->sortFullSchemaAlphabetically()) {
                $this->sortFullSchemaAlphabetically($schemaDefinition);
            }
            // Store in the cache
            if ($useCache) {
                $persistentCache->storeCache($cacheKey, $cacheType, $schemaDefinition);
            }
        }
        return $schemaDefinition;
    }
    /**
     * @return array<string,mixed>
     */
    protected function getSchemaExtensions() : array
    {
        return [\PoPAPI\API\Schema\SchemaDefinition::SCHEMA_IS_NAMESPACED => App::getState('namespace-types-and-interfaces')];
    }
    /**
     * @param array<string,mixed> $schemaDefinition
     */
    public function sortFullSchemaAlphabetically(&$schemaDefinition) : void
    {
        // Sort types
        /** @var string $typeKind */
        foreach (\array_keys($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES]) as $typeKind) {
            \ksort($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][$typeKind]);
        }
        // Sort fields and interfaces for each ObjectType
        /** @var string $typeName */
        foreach (\array_keys($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::OBJECT]) as $typeName) {
            if (isset($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::OBJECT][$typeName][\PoPAPI\API\Schema\SchemaDefinition::FIELDS])) {
                \ksort($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::OBJECT][$typeName][\PoPAPI\API\Schema\SchemaDefinition::FIELDS]);
            }
            if (isset($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::OBJECT][$typeName][\PoPAPI\API\Schema\SchemaDefinition::INTERFACES])) {
                \ksort($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::OBJECT][$typeName][\PoPAPI\API\Schema\SchemaDefinition::INTERFACES]);
            }
        }
        // Sort global fields
        if (isset($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::GLOBAL_FIELDS])) {
            \ksort($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::GLOBAL_FIELDS]);
        }
        foreach (\array_keys($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::UNION] ?? []) as $typeName) {
            \ksort($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::UNION][$typeName][\PoPAPI\API\Schema\SchemaDefinition::POSSIBLE_TYPES]);
        }
        // Sort fields for each InterfaceType
        /** @var string $typeName */
        foreach (\array_keys($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::INTERFACE] ?? []) as $typeName) {
            if (isset($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::INTERFACE][$typeName][\PoPAPI\API\Schema\SchemaDefinition::FIELDS])) {
                \ksort($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::INTERFACE][$typeName][\PoPAPI\API\Schema\SchemaDefinition::FIELDS]);
            }
            if (isset($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::INTERFACE][$typeName][\PoPAPI\API\Schema\SchemaDefinition::INTERFACES])) {
                \ksort($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::INTERFACE][$typeName][\PoPAPI\API\Schema\SchemaDefinition::INTERFACES]);
            }
            \ksort($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::INTERFACE][$typeName][\PoPAPI\API\Schema\SchemaDefinition::POSSIBLE_TYPES]);
        }
        // Sort input fields for each InputObjectType
        /** @var string $typeName */
        foreach (\array_keys($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::INPUT_OBJECT] ?? []) as $typeName) {
            if (isset($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::INPUT_OBJECT][$typeName][\PoPAPI\API\Schema\SchemaDefinition::INPUT_FIELDS])) {
                \ksort($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::INPUT_OBJECT][$typeName][\PoPAPI\API\Schema\SchemaDefinition::INPUT_FIELDS]);
            }
        }
        // Sort values for each EnumType
        /** @var string $typeName */
        foreach (\array_keys($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::ENUM] ?? []) as $typeName) {
            if (isset($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::ENUM][$typeName][\PoPAPI\API\Schema\SchemaDefinition::ITEMS])) {
                \ksort($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::ENUM][$typeName][\PoPAPI\API\Schema\SchemaDefinition::ITEMS]);
            }
        }
        // Sort possibleValues for each "Enum String" Type
        /** @var string $typeName */
        foreach (\array_keys($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::SCALAR] ?? []) as $typeName) {
            if (isset($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::SCALAR][$typeName][\PoPAPI\API\Schema\SchemaDefinition::POSSIBLE_VALUES])) {
                \ksort($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][\PoPAPI\API\Schema\TypeKinds::SCALAR][$typeName][\PoPAPI\API\Schema\SchemaDefinition::POSSIBLE_VALUES]);
            }
        }
        // Sort directives
        if (isset($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::DIRECTIVES])) {
            \ksort($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::DIRECTIVES]);
        }
        if (isset($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::GLOBAL_DIRECTIVES])) {
            \ksort($schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::GLOBAL_DIRECTIVES]);
        }
    }
    /**
     * @param array<TypeResolverInterface|FieldDirectiveResolverInterface> $accessedTypeAndFieldDirectiveResolvers
     */
    private function addAccessedTypeAndFieldDirectiveResolvers(array $accessedTypeAndFieldDirectiveResolvers) : void
    {
        // Add further accessed TypeResolvers and DirectiveResolvers to the stack and keep iterating
        foreach ($accessedTypeAndFieldDirectiveResolvers as $accessedTypeOrFieldDirectiveResolver) {
            if (\in_array(\get_class($accessedTypeOrFieldDirectiveResolver), $this->processedTypeAndFieldDirectiveResolverClasses)) {
                continue;
            }
            $this->pendingTypeOrFieldDirectiveResolvers[] = $accessedTypeOrFieldDirectiveResolver;
        }
    }
    /**
     * @param array<string,mixed> $schemaDefinition
     */
    private function addTypeSchemaDefinition(TypeResolverInterface $typeResolver, array &$schemaDefinition) : void
    {
        $schemaDefinitionProvider = $this->getTypeResolverSchemaDefinitionProvider($typeResolver);
        $typeKind = $schemaDefinitionProvider->getTypeKind();
        $typeName = $typeResolver->getMaybeNamespacedTypeName();
        $typeSchemaDefinition = $schemaDefinitionProvider->getSchemaDefinition();
        /**
         * The RootObject has the special role of also calculating the
         * global fields, connections and directives
         */
        if ($typeResolver === $this->getSchemaRootObjectTypeResolver()) {
            $this->maybeMoveGlobalTypeSchemaDefinition($schemaDefinition, $typeSchemaDefinition);
        }
        $schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::TYPES][$typeKind][$typeName] = $typeSchemaDefinition;
        $this->addAccessedTypeAndFieldDirectiveResolvers($schemaDefinitionProvider->getAccessedTypeAndFieldDirectiveResolvers());
        $this->accessedFieldDirectiveResolverClassRelationalTypeResolvers = \array_merge($this->accessedFieldDirectiveResolverClassRelationalTypeResolvers, $schemaDefinitionProvider->getAccessedFieldDirectiveResolverClassRelationalTypeResolvers());
        /**
         * ObjectTypeResolvers must be injected into the POSSIBLE_TYPES of their implemented InterfaceTypes
         */
        if ($typeResolver instanceof ObjectTypeResolverInterface) {
            /** @var ObjectTypeResolverInterface */
            $objectTypeResolver = $typeResolver;
            /** @var ObjectTypeSchemaDefinitionProvider */
            $objectTypeSchemaDefinitionProvider = $schemaDefinitionProvider;
            foreach ($objectTypeSchemaDefinitionProvider->getImplementedInterfaceTypeResolvers() as $implementedInterfaceTypeResolver) {
                $implementedInterfaceTypeName = $implementedInterfaceTypeResolver->getMaybeNamespacedTypeName();
                $this->accessedInterfaceTypeNameObjectTypeResolvers[$implementedInterfaceTypeName] = $this->accessedInterfaceTypeNameObjectTypeResolvers[$implementedInterfaceTypeName] ?? [];
                $this->accessedInterfaceTypeNameObjectTypeResolvers[$implementedInterfaceTypeName][] = $objectTypeResolver;
            }
        }
    }
    /**
     * Move the definition for the global fields, connections and directives
     * @param array<string,mixed> $schemaDefinition
     * @param array<string,mixed> $rootTypeSchemaDefinition
     */
    private function maybeMoveGlobalTypeSchemaDefinition(array &$schemaDefinition, array &$rootTypeSchemaDefinition) : void
    {
        unset($rootTypeSchemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::GLOBAL_DIRECTIVES]);
        if ($this->skipExposingGlobalFieldsInSchema()) {
            return;
        }
        $schemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::GLOBAL_FIELDS] = $rootTypeSchemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::GLOBAL_FIELDS];
        unset($rootTypeSchemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::GLOBAL_FIELDS]);
    }
    /**
     * Global fields are only added if enabled
     */
    protected function skipExposingGlobalFieldsInSchema() : bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->skipExposingGlobalFieldsInFullSchema();
    }
    /**
     * @param array<string,mixed> $schemaDefinition
     */
    private function addDirectiveSchemaDefinition(FieldDirectiveResolverInterface $directiveResolver, array &$schemaDefinition) : void
    {
        $relationalTypeResolver = $this->accessedFieldDirectiveResolverClassRelationalTypeResolvers[\get_class($directiveResolver)];
        $schemaDefinitionProvider = new DirectiveSchemaDefinitionProvider($directiveResolver, $relationalTypeResolver);
        $directiveName = $directiveResolver->getDirectiveName();
        $directiveSchemaDefinition = $schemaDefinitionProvider->getSchemaDefinition();
        $entry = $directiveSchemaDefinition[\PoPAPI\API\Schema\SchemaDefinition::DIRECTIVE_IS_GLOBAL] ? \PoPAPI\API\Schema\SchemaDefinition::GLOBAL_DIRECTIVES : \PoPAPI\API\Schema\SchemaDefinition::DIRECTIVES;
        $schemaDefinition[$entry][$directiveName] = $directiveSchemaDefinition;
        $this->addAccessedTypeAndFieldDirectiveResolvers($schemaDefinitionProvider->getAccessedTypeAndFieldDirectiveResolvers());
    }
    /**
     * @throws ImpossibleToHappenException If the TypeResolver does not belong to any of the known groups
     * @param \PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver
     */
    protected function getTypeResolverSchemaDefinitionProvider($typeResolver) : TypeSchemaDefinitionProviderInterface
    {
        if ($typeResolver instanceof ObjectTypeResolverInterface) {
            /**
             * The RootObject has the special role of also calculating the
             * global fields, connections and directives
             */
            if ($typeResolver === $this->getSchemaRootObjectTypeResolver()) {
                return $this->createRootObjectTypeSchemaDefinitionProvider($typeResolver);
            }
            return new ObjectTypeSchemaDefinitionProvider($typeResolver);
        }
        if ($typeResolver instanceof InterfaceTypeResolverInterface) {
            return new InterfaceTypeSchemaDefinitionProvider($typeResolver);
        }
        if ($typeResolver instanceof UnionTypeResolverInterface) {
            return new UnionTypeSchemaDefinitionProvider($typeResolver);
        }
        if ($typeResolver instanceof ScalarTypeResolverInterface) {
            return new ScalarTypeSchemaDefinitionProvider($typeResolver);
        }
        if ($typeResolver instanceof EnumTypeResolverInterface) {
            return new EnumTypeSchemaDefinitionProvider($typeResolver);
        }
        if ($typeResolver instanceof InputObjectTypeResolverInterface) {
            return new InputObjectTypeSchemaDefinitionProvider($typeResolver);
        }
        throw new ImpossibleToHappenException(\sprintf($this->__('No type identified for TypeResolver with class \'%s\'', 'api'), \get_class($typeResolver)));
    }
    /**
     * @param \PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver $rootObjectTypeResolver
     */
    protected function createRootObjectTypeSchemaDefinitionProvider($rootObjectTypeResolver) : RootObjectTypeSchemaDefinitionProvider
    {
        return new RootObjectTypeSchemaDefinitionProvider($rootObjectTypeResolver);
    }
}

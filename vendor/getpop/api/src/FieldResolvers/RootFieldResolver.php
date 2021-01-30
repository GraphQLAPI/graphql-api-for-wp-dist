<?php

declare (strict_types=1);
namespace PoP\API\FieldResolvers;

use PoP\API\Cache\CacheTypes;
use PoP\API\Cache\CacheUtils;
use PoP\API\ComponentConfiguration;
use PoP\API\Schema\SchemaDefinition;
use PoP\API\Enums\SchemaFieldShapeEnum;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\API\Facades\PersistedQueryManagerFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\API\Facades\PersistedFragmentManagerFacade;
use PoP\ComponentModel\Facades\Cache\PersistentCacheFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade;
class RootFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoP\Engine\TypeResolvers\RootTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['fullSchema'];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['fullSchema' => \PoP\API\Schema\SchemaDefinition::TYPE_OBJECT];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        switch ($fieldName) {
            case 'fullSchema':
                return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['fullSchema' => $translationAPI->__('The whole API schema, exposing what fields can be queried', '')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'fullSchema':
                /**
                 * @var SchemaFieldShapeEnum
                 */
                $schemaOutputShapeEnum = $instanceManager->getInstance(\PoP\API\Enums\SchemaFieldShapeEnum::class);
                return \array_merge($schemaFieldArgs, [[\PoP\API\Schema\SchemaDefinition::ARGNAME_NAME => 'deep', \PoP\API\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\API\Schema\SchemaDefinition::TYPE_BOOL, \PoP\API\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Make a deep introspection of the fields, for all nested objects', ''), \PoP\API\Schema\SchemaDefinition::ARGNAME_DEFAULT_VALUE => \true], [\PoP\API\Schema\SchemaDefinition::ARGNAME_NAME => 'shape', \PoP\API\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\API\Schema\SchemaDefinition::TYPE_ENUM, \PoP\API\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => \sprintf($translationAPI->__('How to shape the schema output: \'%s\', in which case all types are listed together, or \'%s\', in which the types are listed following where they appear in the graph', ''), \PoP\API\Schema\SchemaDefinition::ARGVALUE_SCHEMA_SHAPE_FLAT, \PoP\API\Schema\SchemaDefinition::ARGVALUE_SCHEMA_SHAPE_NESTED), \PoP\API\Schema\SchemaDefinition::ARGNAME_ENUM_NAME => $schemaOutputShapeEnum->getName(), \PoP\API\Schema\SchemaDefinition::ARGNAME_ENUM_VALUES => \PoP\ComponentModel\Schema\SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions($schemaOutputShapeEnum->getValues()), \PoP\API\Schema\SchemaDefinition::ARGNAME_DEFAULT_VALUE => \PoP\API\Schema\SchemaDefinition::ARGVALUE_SCHEMA_SHAPE_FLAT], [\PoP\API\Schema\SchemaDefinition::ARGNAME_NAME => 'compressed', \PoP\API\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\API\Schema\SchemaDefinition::TYPE_BOOL, \PoP\API\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Output each resolver\'s schema data only once to compress the output. Valid only when field \'deep\' is `true`', ''), \PoP\API\Schema\SchemaDefinition::ARGNAME_DEFAULT_VALUE => \false], [\PoP\API\Schema\SchemaDefinition::ARGNAME_NAME => 'useTypeName', \PoP\API\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\API\Schema\SchemaDefinition::TYPE_BOOL, \PoP\API\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => \sprintf($translationAPI->__('Replace type \'%s\' with the actual type name (such as \'Post\')', ''), \PoP\API\Schema\SchemaDefinition::TYPE_ID), \PoP\API\Schema\SchemaDefinition::ARGNAME_DEFAULT_VALUE => \true]]);
        }
        return $schemaFieldArgs;
    }
    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     * @param object $resultItem
     */
    public function resolveValue(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $root = $resultItem;
        switch ($fieldName) {
            case 'fullSchema':
                // Attempt to retrieve from the cache, if enabled
                if ($useCache = \PoP\API\ComponentConfiguration::useSchemaDefinitionCache()) {
                    $persistentCache = \PoP\ComponentModel\Facades\Cache\PersistentCacheFacade::getInstance();
                    // Use different caches for the normal and namespaced schemas, or
                    // it throws exception if switching without deleting the cache (eg: when passing ?use_namespace=1)
                    $cacheType = \PoP\API\Cache\CacheTypes::FULLSCHEMA_DEFINITION;
                    $cacheKeyComponents = \PoP\API\Cache\CacheUtils::getSchemaCacheKeyComponents();
                    // For the persistentCache, use a hash to remove invalid characters (such as "()")
                    $cacheKey = \hash('md5', \json_encode($cacheKeyComponents));
                }
                $schemaDefinition = null;
                if ($useCache) {
                    if ($persistentCache->hasCache($cacheKey, $cacheType)) {
                        $schemaDefinition = $persistentCache->getCache($cacheKey, $cacheType);
                    }
                }
                if ($schemaDefinition === null) {
                    $schemaDefinitionService = \PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade::getInstance();
                    $stackMessages = ['processed' => []];
                    $generalMessages = ['processed' => []];
                    $rootTypeSchemaKey = $schemaDefinitionService->getTypeSchemaKey($typeResolver);
                    // Normalize properties in $fieldArgs with their defaults
                    // By default make it deep. To avoid it, must pass argument (deep:false)
                    // By default, use the "flat" shape
                    $schemaOptions = \array_merge($options, ['deep' => $fieldArgs['deep'], 'compressed' => $fieldArgs['compressed'], 'shape' => $fieldArgs['shape'], 'useTypeName' => $fieldArgs['useTypeName']]);
                    // If it is flat shape, all types will be added under $generalMessages
                    $isFlatShape = $schemaOptions['shape'] == \PoP\API\Schema\SchemaDefinition::ARGVALUE_SCHEMA_SHAPE_FLAT;
                    if ($isFlatShape) {
                        $generalMessages[\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES] = [];
                    }
                    $typeSchemaDefinition = $typeResolver->getSchemaDefinition($stackMessages, $generalMessages, $schemaOptions);
                    $schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES] = $typeSchemaDefinition;
                    // Add the queryType
                    $schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_QUERY_TYPE] = $rootTypeSchemaKey;
                    // Move from under Root type to the top: globalDirectives and globalFields (renamed as "functions")
                    $schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_FIELDS] = $typeSchemaDefinition[$rootTypeSchemaKey][\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_FIELDS] ?? [];
                    $schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_CONNECTIONS] = $typeSchemaDefinition[$rootTypeSchemaKey][\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_CONNECTIONS] ?? [];
                    $schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES] = $typeSchemaDefinition[$rootTypeSchemaKey][\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES] ?? [];
                    unset($schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES][$rootTypeSchemaKey][\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_FIELDS]);
                    unset($schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES][$rootTypeSchemaKey][\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_CONNECTIONS]);
                    unset($schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES][$rootTypeSchemaKey][\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES]);
                    // Retrieve the list of all types from under $generalMessages
                    if ($isFlatShape) {
                        $typeFlatList = $generalMessages[\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES];
                        // Remove the globals from the Root
                        unset($typeFlatList[$rootTypeSchemaKey][\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_FIELDS]);
                        unset($typeFlatList[$rootTypeSchemaKey][\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_CONNECTIONS]);
                        unset($typeFlatList[$rootTypeSchemaKey][\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES]);
                        // Because they were added in reverse way, reverse it once again, so that the first types (eg: Root) appear first
                        $schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES] = \array_reverse($typeFlatList);
                        // Add the interfaces to the root
                        $interfaces = [];
                        foreach ($schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES] as $typeName => $typeDefinition) {
                            if ($typeInterfaces = $typeDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_INTERFACES] ?? null) {
                                $interfaces = \array_merge($interfaces, (array) $typeInterfaces);
                                // Keep only the name of the interface under the type
                                $schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES][$typeName][\PoP\API\Schema\SchemaDefinition::ARGNAME_INTERFACES] = \array_keys((array) $schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES][$typeName][\PoP\API\Schema\SchemaDefinition::ARGNAME_INTERFACES]);
                            }
                        }
                        $schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_INTERFACES] = $interfaces;
                    }
                    // Add the Fragment Catalogue
                    $fragmentCatalogueManager = \PoP\API\Facades\PersistedFragmentManagerFacade::getInstance();
                    $persistedFragments = $fragmentCatalogueManager->getPersistedFragmentsForSchema();
                    $schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_PERSISTED_FRAGMENTS] = $persistedFragments;
                    // Add the Query Catalogue
                    $queryCatalogueManager = \PoP\API\Facades\PersistedQueryManagerFacade::getInstance();
                    $persistedQueries = $queryCatalogueManager->getPersistedQueriesForSchema();
                    $schemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_PERSISTED_QUERIES] = $persistedQueries;
                    // Store in the cache
                    if ($useCache) {
                        $persistentCache->storeCache($cacheKey, $cacheType, $schemaDefinition);
                    }
                }
                return $schemaDefinition;
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

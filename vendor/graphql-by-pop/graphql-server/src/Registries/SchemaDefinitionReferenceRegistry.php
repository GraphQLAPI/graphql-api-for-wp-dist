<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Registries;

use PoP\API\Cache\CacheUtils;
use GraphQLByPoP\GraphQLServer\Environment;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\Cache\CacheTypes;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\API\Facades\SchemaDefinitionRegistryFacade;
use GraphQLByPoP\GraphQLQuery\Schema\SchemaElements;
use GraphQLByPoP\GraphQLServer\Schema\SchemaHelpers;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use PoP\ComponentModel\Facades\Cache\PersistentCacheFacade;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Facades\Schema\GraphQLSchemaDefinitionServiceFacade;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition as GraphQLServerSchemaDefinition;
use GraphQLByPoP\GraphQLQuery\ComponentConfiguration as GraphQLQueryComponentConfiguration;
class SchemaDefinitionReferenceRegistry implements \GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface
{
    /**
     * @var array<string, mixed>
     */
    protected $fullSchemaDefinition = null;
    /**
     * @var array<string, AbstractSchemaDefinitionReferenceObject>
     */
    protected $fullSchemaDefinitionReferenceDictionary = [];
    /**
     * @var AbstractSchemaDefinitionReferenceObject[]
     */
    protected $dynamicTypes = [];
    /**
     * It returns the full schema, expanded with all data required to satisfy
     * GraphQL's introspection fields (starting from "__schema")
     *
     * It can store the value in the cache.
     * Use cache with care: if the schema is dynamic, it should not be cached.
     * Public schema: can cache, Private schema: cannot cache.
     *
     * @return array
     */
    public function &getFullSchemaDefinition() : array
    {
        if (\is_null($this->fullSchemaDefinition)) {
            // These are the configuration options to work with the "full schema"
            $fieldArgs = ['deep' => \true, 'shape' => \PoP\ComponentModel\Schema\SchemaDefinition::ARGVALUE_SCHEMA_SHAPE_FLAT, 'compressed' => \true, 'useTypeName' => \true];
            // Attempt to retrieve from the cache, if enabled
            if ($useCache = \PoP\API\ComponentConfiguration::useSchemaDefinitionCache()) {
                $persistentCache = \PoP\ComponentModel\Facades\Cache\PersistentCacheFacade::getInstance();
                // Use different caches for the normal and namespaced schemas,
                // or it throws exception if switching without deleting the cache (eg: when passing ?use_namespace=1)
                $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
                $cacheType = \GraphQLByPoP\GraphQLServer\Cache\CacheTypes::GRAPHQL_SCHEMA_DEFINITION;
                $cacheKeyComponents = \array_merge($fieldArgs, \PoP\API\Cache\CacheUtils::getSchemaCacheKeyComponents(), ['edit-schema' => isset($vars['edit-schema']) && $vars['edit-schema']]);
                // For the persistentCache, use a hash to remove invalid characters (such as "()")
                $cacheKey = \hash('md5', \json_encode($cacheKeyComponents));
            }
            if ($useCache) {
                if ($persistentCache->hasCache($cacheKey, $cacheType)) {
                    $this->fullSchemaDefinition = $persistentCache->getCache($cacheKey, $cacheType);
                }
            }
            // If either not using cache, or using but the value had not been cached, then calculate the value
            if (!$this->fullSchemaDefinition) {
                // Get the schema definitions
                $schemaDefinitionRegistry = \PoP\API\Facades\SchemaDefinitionRegistryFacade::getInstance();
                $this->fullSchemaDefinition = $schemaDefinitionRegistry->getSchemaDefinition($fieldArgs);
                // Convert the schema from PoP's format to what GraphQL needs to work with
                $this->prepareSchemaDefinitionForGraphQL();
                // Store in the cache
                if ($useCache) {
                    $persistentCache->storeCache($cacheKey, $cacheType, $this->fullSchemaDefinition);
                }
            }
        }
        return $this->fullSchemaDefinition;
    }
    protected function prepareSchemaDefinitionForGraphQL() : void
    {
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        $enableNestedMutations = $vars['nested-mutations-enabled'];
        $graphQLSchemaDefinitionService = \GraphQLByPoP\GraphQLServer\Facades\Schema\GraphQLSchemaDefinitionServiceFacade::getInstance();
        $rootTypeSchemaKey = $graphQLSchemaDefinitionService->getRootTypeSchemaKey();
        if (!$enableNestedMutations) {
            $queryRootTypeSchemaKey = $graphQLSchemaDefinitionService->getQueryRootTypeSchemaKey();
        }
        // Remove the introspection fields that must not be added to the schema
        // Field "__typename" from all types (GraphQL spec @ https://graphql.github.io/graphql-spec/draft/#sel-FAJZHABFBKjrL):
        // "This field is implicit and does not appear in the fields list in any defined type."
        unset($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_FIELDS]['__typename']);
        // Fields "__schema" and "__type" from the query type (GraphQL spec @ https://graphql.github.io/graphql-spec/draft/#sel-FAJbHABABnD9ub):
        // "These fields are implicit and do not appear in the fields list in the root type of the query operation."
        unset($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES][$rootTypeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_CONNECTIONS]['__type']);
        unset($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES][$rootTypeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_CONNECTIONS]['__schema']);
        if (!$enableNestedMutations) {
            unset($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES][$queryRootTypeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_CONNECTIONS]['__type']);
            unset($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES][$queryRootTypeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_CONNECTIONS]['__schema']);
        }
        // Remove unneeded data
        if (!\GraphQLByPoP\GraphQLServer\Environment::addGlobalFieldsToSchema()) {
            unset($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_FIELDS]);
            unset($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_CONNECTIONS]);
        }
        if (!\GraphQLByPoP\GraphQLServer\Environment::addSelfFieldToSchema()) {
            /**
             * Check if to remove the "self" field everywhere, or if to keep it just for the Root type
             */
            $keepSelfFieldForRootType = \GraphQLByPoP\GraphQLServer\ComponentConfiguration::addSelfFieldForRootTypeToSchema();
            foreach (\array_keys($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES]) as $typeSchemaKey) {
                if (!$keepSelfFieldForRootType || $typeSchemaKey != $rootTypeSchemaKey && ($enableNestedMutations || $typeSchemaKey != $queryRootTypeSchemaKey)) {
                    unset($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_CONNECTIONS]['self']);
                }
            }
        }
        if (!\GraphQLByPoP\GraphQLServer\Environment::addFullSchemaFieldToSchema()) {
            unset($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES][$rootTypeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_FIELDS]['fullSchema']);
            if (!$enableNestedMutations) {
                unset($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES][$queryRootTypeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_FIELDS]['fullSchema']);
            }
        }
        // Maybe append the field/directive's version to its description, since this field is missing in GraphQL
        $addVersionToSchemaFieldDescription = \GraphQLByPoP\GraphQLServer\Environment::addVersionToSchemaFieldDescription();
        // When doing nested mutations, differentiate mutating fields by adding label "[Mutation]" in the description
        $addMutationLabelToSchemaFieldDescription = $enableNestedMutations;
        // Maybe add param "nestedUnder" on the schema for each directive
        $enableComposableDirectives = \GraphQLByPoP\GraphQLQuery\ComponentConfiguration::enableComposableDirectives();
        // Convert the field type from its internal representation (eg: "array:Post") to the GraphQL standard representation (eg: "[Post]")
        // 1. Global fields, connections and directives
        if (\GraphQLByPoP\GraphQLServer\Environment::addGlobalFieldsToSchema()) {
            foreach (\array_keys($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_FIELDS]) as $fieldName) {
                $itemPath = [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_FIELDS, $fieldName];
                $this->introduceSDLNotationToFieldSchemaDefinition($itemPath);
                if ($addVersionToSchemaFieldDescription) {
                    $this->addVersionToSchemaFieldDescription($itemPath);
                }
                if ($addMutationLabelToSchemaFieldDescription) {
                    $this->addMutationLabelToSchemaFieldDescription($itemPath);
                }
            }
            foreach (\array_keys($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_CONNECTIONS]) as $connectionName) {
                $itemPath = [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_CONNECTIONS, $connectionName];
                $this->introduceSDLNotationToFieldSchemaDefinition($itemPath);
                if ($addVersionToSchemaFieldDescription) {
                    $this->addVersionToSchemaFieldDescription($itemPath);
                }
                if ($addMutationLabelToSchemaFieldDescription) {
                    $this->addMutationLabelToSchemaFieldDescription($itemPath);
                }
            }
        }
        // Remove all directives of types other than "Query" and "Schema"
        // since GraphQL only supports these 2
        $supportedDirectiveTypes = [\PoP\ComponentModel\Directives\DirectiveTypes::SCHEMA, \PoP\ComponentModel\Directives\DirectiveTypes::QUERY];
        $directivesNamesToRemove = [];
        foreach (\array_keys($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES]) as $directiveName) {
            if (!\in_array($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES][$directiveName][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DIRECTIVE_TYPE], $supportedDirectiveTypes)) {
                $directivesNamesToRemove[] = $directiveName;
            }
        }
        foreach ($directivesNamesToRemove as $directiveName) {
            unset($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES][$directiveName]);
        }
        // Add the directives
        foreach (\array_keys($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES]) as $directiveName) {
            $itemPath = [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES, $directiveName];
            $fieldOrDirectiveSchemaDefinition =& \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $itemPath);
            $this->introduceSDLNotationToFieldOrDirectiveArgs($itemPath);
            if ($enableComposableDirectives) {
                $this->addNestedDirectiveDataToSchemaDirectiveArgs($itemPath);
            }
            if ($addVersionToSchemaFieldDescription) {
                $this->addVersionToSchemaFieldDescription($itemPath);
            }
            $this->maybeAddTypeToSchemaDirectiveDescription($itemPath);
        }
        // 2. Each type's fields, connections and directives
        foreach ($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES] as $typeSchemaKey => $typeSchemaDefinition) {
            // No need for Union types
            if ($typeSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_IS_UNION] ?? null) {
                continue;
            }
            foreach (\array_keys($typeSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_FIELDS]) as $fieldName) {
                $itemPath = [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES, $typeSchemaKey, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_FIELDS, $fieldName];
                $this->introduceSDLNotationToFieldSchemaDefinition($itemPath);
                if ($addVersionToSchemaFieldDescription) {
                    $this->addVersionToSchemaFieldDescription($itemPath);
                }
                if ($addMutationLabelToSchemaFieldDescription) {
                    $this->addMutationLabelToSchemaFieldDescription($itemPath);
                }
            }
            foreach (\array_keys($typeSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_CONNECTIONS]) as $connectionName) {
                $itemPath = [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES, $typeSchemaKey, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_CONNECTIONS, $connectionName];
                $this->introduceSDLNotationToFieldSchemaDefinition($itemPath);
                if ($addVersionToSchemaFieldDescription) {
                    $this->addVersionToSchemaFieldDescription($itemPath);
                }
                if ($addMutationLabelToSchemaFieldDescription) {
                    $this->addMutationLabelToSchemaFieldDescription($itemPath);
                }
            }
            foreach (\array_keys($typeSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DIRECTIVES]) as $directiveName) {
                $itemPath = [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES, $typeSchemaKey, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DIRECTIVES, $directiveName];
                $this->introduceSDLNotationToFieldOrDirectiveArgs($itemPath);
                if ($enableComposableDirectives) {
                    $this->addNestedDirectiveDataToSchemaDirectiveArgs($itemPath);
                }
                if ($addVersionToSchemaFieldDescription) {
                    $this->addVersionToSchemaFieldDescription($itemPath);
                }
            }
        }
        // 3. Interfaces
        foreach ($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_INTERFACES] as $interfaceName => $interfaceSchemaDefinition) {
            foreach (\array_keys($interfaceSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_FIELDS]) as $fieldName) {
                $itemPath = [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_INTERFACES, $interfaceName, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_FIELDS, $fieldName];
                $this->introduceSDLNotationToFieldSchemaDefinition($itemPath);
                // if ($addVersionToSchemaFieldDescription) {
                //     $this->addVersionToSchemaFieldDescription($itemPath);
                // }
            }
        }
        // Sort the elements in the schema alphabetically
        if (\GraphQLByPoP\GraphQLServer\ComponentConfiguration::sortSchemaAlphabetically()) {
            // Sort types
            \ksort($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES]);
            // Sort fields, connections and interfaces for each type
            foreach ($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES] as $typeSchemaKey => $typeSchemaDefinition) {
                if (isset($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_FIELDS])) {
                    \ksort($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_FIELDS]);
                }
                if (isset($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_CONNECTIONS])) {
                    \ksort($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_CONNECTIONS]);
                }
                if (isset($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_INTERFACES])) {
                    \sort($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES][$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_INTERFACES]);
                }
            }
            // Sort directives
            if (isset($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES])) {
                \ksort($this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES]);
            }
            /**
             * Can NOT sort interfaces yet! Because interfaces may depend on other interfaces,
             * they must follow their current order to be initialized,
             * which happens when creating instances of `InterfaceType` in type `Schema`
             *
             * @todo Find a workaround if interfaces need to be sorted
             */
            // if (isset($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_INTERFACES])) {
            //     ksort($this->fullSchemaDefinition[SchemaDefinition::ARGNAME_INTERFACES]);
            // }
        }
        // Expand the full schema with more data that is needed for GraphQL
        // Add the scalar types
        $scalarTypeNames = [\GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_ID, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_STRING, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_INT, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_FLOAT, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_BOOL, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_OBJECT, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_MIXED, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_DATE, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_TIME, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_URL, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_EMAIL, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_IP];
        foreach ($scalarTypeNames as $scalarTypeName) {
            $this->fullSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPES][$scalarTypeName] = [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => $scalarTypeName, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAMESPACED_NAME => $scalarTypeName, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ELEMENT_NAME => $scalarTypeName, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => null, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DIRECTIVES => null, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_FIELDS => null, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_CONNECTIONS => null, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_INTERFACES => null];
        }
    }
    /**
     * Convert the field type from its internal representation (eg: "array:Post") to the GraphQL standard representation (eg: "[Post]")
     *
     * @param array $fieldSchemaDefinitionPath
     * @return void
     */
    protected function introduceSDLNotationToFieldSchemaDefinition(array $fieldSchemaDefinitionPath) : void
    {
        $fieldSchemaDefinition =& \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $fieldSchemaDefinitionPath);
        if ($type = $fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE] ?? null) {
            $fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE] = \GraphQLByPoP\GraphQLServer\Schema\SchemaHelpers::getTypeToOutputInSchema($type, $fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NON_NULLABLE] ?? null);
        }
        $this->introduceSDLNotationToFieldOrDirectiveArgs($fieldSchemaDefinitionPath);
    }
    protected function introduceSDLNotationToFieldOrDirectiveArgs(array $fieldOrDirectiveSchemaDefinitionPath) : void
    {
        $fieldOrDirectiveSchemaDefinition =& \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $fieldOrDirectiveSchemaDefinitionPath);
        // Also for the fieldOrDirective arguments
        if ($fieldOrDirectiveArgs = $fieldOrDirectiveSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ARGS] ?? null) {
            foreach ($fieldOrDirectiveArgs as $fieldOrDirectiveArgName => $fieldOrDirectiveArgSchemaDefinition) {
                if ($type = $fieldOrDirectiveArgSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE] ?? null) {
                    $fieldOrDirectiveSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ARGS][$fieldOrDirectiveArgName][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE] = \GraphQLByPoP\GraphQLServer\Schema\SchemaHelpers::getTypeToOutputInSchema($type, $fieldOrDirectiveArgSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY] ?? null);
                    // If it is an input object, it may have its own args to also convert
                    if ($type == \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_INPUT_OBJECT) {
                        foreach ($fieldOrDirectiveArgSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ARGS] ?? [] as $inputFieldArgName => $inputFieldArgDefinition) {
                            $inputFieldType = $inputFieldArgDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE];
                            $fieldOrDirectiveSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ARGS][$fieldOrDirectiveArgName][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ARGS][$inputFieldArgName][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE] = \GraphQLByPoP\GraphQLServer\Schema\SchemaHelpers::getTypeToOutputInSchema($inputFieldType, $inputFieldArgDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY] ?? null);
                        }
                    }
                }
            }
        }
    }
    /**
     * When doing /?edit_schema=true, "Schema" type directives will also be added the FIELD location,
     * so that they show up in GraphiQL and can be added to a persisted query
     * When that happens, append '("Schema" type directive)' to the directive's description
     *
     * @param array $directiveSchemaDefinitionPath
     * @return void
     */
    protected function maybeAddTypeToSchemaDirectiveDescription(array $directiveSchemaDefinitionPath) : void
    {
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        if (isset($vars['edit-schema']) && $vars['edit-schema']) {
            $directiveSchemaDefinition =& \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $directiveSchemaDefinitionPath);
            if ($directiveSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DIRECTIVE_TYPE] == \PoP\ComponentModel\Directives\DirectiveTypes::SCHEMA) {
                $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
                $directiveSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION] = \sprintf($translationAPI->__('%s %s', 'graphql-server'), \sprintf(
                    '_%s_',
                    // Make it italic using markdown
                    $translationAPI->__('("Schema" type directive)', 'graphql-server')
                ), $directiveSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION]);
            }
        }
    }
    /**
     * Append the field or directive's version to its description
     *
     * @param array $fieldOrDirectiveSchemaDefinitionPath
     */
    protected function addVersionToSchemaFieldDescription(array $fieldOrDirectiveSchemaDefinitionPath) : void
    {
        $fieldOrDirectiveSchemaDefinition =& \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $fieldOrDirectiveSchemaDefinitionPath);
        if ($schemaFieldVersion = $fieldOrDirectiveSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_VERSION] ?? null) {
            $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
            $fieldOrDirectiveSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION] .= \sprintf(\sprintf(
                $translationAPI->__(' _%s_', 'graphql-server'),
                // Make it italic using markdown
                $translationAPI->__('(Version: %s)', 'graphql-server')
            ), $schemaFieldVersion);
        }
    }
    /**
     * Append param "nestedUnder" to the directive
     *
     * @param array $directiveSchemaDefinitionPath
     */
    protected function addNestedDirectiveDataToSchemaDirectiveArgs(array $directiveSchemaDefinitionPath) : void
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $directiveSchemaDefinition =& \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $directiveSchemaDefinitionPath);
        $directiveSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ARGS] = $directiveSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ARGS] ?? [];
        $directiveSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ARGS][] = [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \GraphQLByPoP\GraphQLQuery\Schema\SchemaElements::DIRECTIVE_PARAM_NESTED_UNDER, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_INT, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Nest the directive under another one, indicated as a relative position from this one (a negative int)', 'graphql-server')];
    }
    /**
     * Append the "Mutation" label to the field's description
     *
     * @param array $fieldSchemaDefinitionPath
     */
    protected function addMutationLabelToSchemaFieldDescription(array $fieldSchemaDefinitionPath) : void
    {
        $fieldSchemaDefinition =& \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers::advancePointerToPath($this->fullSchemaDefinition, $fieldSchemaDefinitionPath);
        if ($fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_FIELD_IS_MUTATION] ?? null) {
            $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
            $fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION] = \sprintf($translationAPI->__('[Mutation] %s', 'graphql-server'), $fieldSchemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION]);
        }
    }
    public function registerSchemaDefinitionReference(\GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject $referenceObject) : string
    {
        $schemaDefinitionPath = $referenceObject->getSchemaDefinitionPath();
        $referenceObjectID = \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers::getID($schemaDefinitionPath);
        // Calculate and set the ID. If this is a nested type, its wrapping type will already have been registered under this ID
        // Hence, register it under another one
        while (isset($this->fullSchemaDefinitionReferenceDictionary[$referenceObjectID])) {
            // Append the ID with a distinctive token at the end
            $referenceObjectID .= '*';
        }
        $this->fullSchemaDefinitionReferenceDictionary[$referenceObjectID] = $referenceObject;
        // Dynamic types are stored so that the schema can add them to its "types" field
        if ($referenceObject->isDynamicType()) {
            $this->dynamicTypes[] = $referenceObject;
        }
        return $referenceObjectID;
    }
    public function getSchemaDefinitionReference(string $referenceObjectID) : ?\GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject
    {
        return $this->fullSchemaDefinitionReferenceDictionary[$referenceObjectID];
    }
    public function getDynamicTypes(bool $filterRepeated = \true) : array
    {
        // Watch out! When an ObjectType or InterfaceType implements an interface,
        // and a field of dynamicType (such as "status", which is an ENUM)
        // is covered by the interface, then the field definition will be
        // that one from the interface's perspective.
        // Hence, this field may be registered several times, as coming
        // from different ObjectTypes implementing the same interface!
        // (Eg: both Post and Page have field "status" from interface CustomPost)
        // If $filterRepeated is true, remove instances with a repeated name
        if ($filterRepeated) {
            $dynamicTypes = $typeNames = [];
            foreach ($this->dynamicTypes as $type) {
                $typeName = $type->getName();
                if (!\in_array($typeName, $typeNames)) {
                    $dynamicTypes[] = $type;
                    $typeNames[] = $typeName;
                }
            }
            return $dynamicTypes;
        }
        return $this->dynamicTypes;
    }
}

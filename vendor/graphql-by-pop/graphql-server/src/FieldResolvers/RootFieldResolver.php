<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLByPoP\GraphQLServer\TypeResolvers\SchemaTypeResolver;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use GraphQLByPoP\GraphQLServer\TypeDataLoaders\SchemaTypeDataLoader;
class RootFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoP\Engine\TypeResolvers\RootTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        // Only register them for the standard GraphQL,
        // or for PQL if explicitly enabled
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        if (!$vars['graphql-introspection-enabled']) {
            return [];
        }
        return ['__schema', '__type'];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['__schema' => \PoP\API\Schema\SchemaDefinition::TYPE_ID, '__type' => \PoP\API\Schema\SchemaDefinition::TYPE_ID];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        $nonNullableFieldNames = ['__schema', '__type'];
        if (\in_array($fieldName, $nonNullableFieldNames)) {
            return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['__schema' => $translationAPI->__('The GraphQL schema, exposing what fields can be queried', 'graphql-server'), '__type' => $translationAPI->__('Obtain a specific type from the schema', 'graphql-server')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case '__type':
                return \array_merge($schemaFieldArgs, [[\PoP\API\Schema\SchemaDefinition::ARGNAME_NAME => 'name', \PoP\API\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\API\Schema\SchemaDefinition::TYPE_STRING, \PoP\API\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The name of the type', 'graphql-server'), \PoP\API\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
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
            case '__schema':
                return 'schema';
            case '__type':
                // Get an instance of the schema and then execute function `getType` there
                $schemaID = $typeResolver->resolveValue($resultItem, \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance()->getField('__schema', []), $variables, $expressions, $options);
                if (\PoP\ComponentModel\Misc\GeneralUtils::isError($schemaID)) {
                    return $schemaID;
                }
                // Obtain the instance of the schema
                $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
                /**
                 * @var SchemaTypeDataLoader
                 */
                $schemaTypeDataLoader = $instanceManager->getInstance(\GraphQLByPoP\GraphQLServer\TypeDataLoaders\SchemaTypeDataLoader::class);
                $schemaInstances = $schemaTypeDataLoader->getObjects([$schemaID]);
                $schema = $schemaInstances[0];
                return $schema->getTypeID($fieldArgs['name']);
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case '__schema':
                return \GraphQLByPoP\GraphQLServer\TypeResolvers\SchemaTypeResolver::class;
            case '__type':
                return \GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}

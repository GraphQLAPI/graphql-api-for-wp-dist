<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use PoP\API\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use GraphQLByPoP\GraphQLServer\TypeResolvers\SchemaTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use GraphQLByPoP\GraphQLServer\TypeResolvers\DirectiveTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
class SchemaFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\GraphQLByPoP\GraphQLServer\TypeResolvers\SchemaTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['queryType', 'mutationType', 'subscriptionType', 'types', 'directives', 'type'];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['queryType' => \PoP\API\Schema\SchemaDefinition::TYPE_ID, 'mutationType' => \PoP\API\Schema\SchemaDefinition::TYPE_ID, 'subscriptionType' => \PoP\API\Schema\SchemaDefinition::TYPE_ID, 'types' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\API\Schema\SchemaDefinition::TYPE_ID), 'directives' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\API\Schema\SchemaDefinition::TYPE_ID), 'type' => \PoP\API\Schema\SchemaDefinition::TYPE_ID];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        $nonNullableFieldNames = ['queryType', 'types', 'directives'];
        if (\in_array($fieldName, $nonNullableFieldNames)) {
            return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['queryType' => $translationAPI->__('The type, accessible from the root, that resolves queries', 'graphql-server'), 'mutationType' => $translationAPI->__('The type, accessible from the root, that resolves mutations', 'graphql-server'), 'subscriptionType' => $translationAPI->__('The type, accessible from the root, that resolves subscriptions', 'graphql-server'), 'types' => $translationAPI->__('All types registered in the data graph', 'graphql-server'), 'directives' => $translationAPI->__('All directives registered in the data graph', 'graphql-server'), 'type' => $translationAPI->__('Obtain a specific type from the schema', 'graphql-server')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'type':
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
        $schema = $resultItem;
        switch ($fieldName) {
            case 'queryType':
                return $schema->getQueryTypeID();
            case 'mutationType':
                return $schema->getMutationTypeID();
            case 'subscriptionType':
                return $schema->getSubscriptionTypeID();
            case 'types':
                return $schema->getTypeIDs();
            case 'directives':
                return $schema->getDirectiveIDs();
            case 'type':
                return $schema->getTypeID($fieldArgs['name']);
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'queryType':
            case 'mutationType':
            case 'subscriptionType':
            case 'types':
            case 'type':
                return \GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver::class;
            case 'directives':
                return \GraphQLByPoP\GraphQLServer\TypeResolvers\DirectiveTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}

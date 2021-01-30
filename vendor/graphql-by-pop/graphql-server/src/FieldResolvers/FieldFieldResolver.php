<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\FieldTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\InputValueTypeResolver;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
class FieldFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\GraphQLByPoP\GraphQLServer\TypeResolvers\FieldTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['name', 'description', 'args', 'type', 'isDeprecated', 'deprecationReason', 'extensions'];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['name' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'description' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'args' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID), 'type' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'isDeprecated' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, 'deprecationReason' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'extensions' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED)];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        $nonNullableFieldNames = ['name', 'args', 'type', 'isDeprecated'];
        if (\in_array($fieldName, $nonNullableFieldNames)) {
            return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['name' => $translationAPI->__('Field\'s name', 'graphql-server'), 'description' => $translationAPI->__('Field\'s description', 'graphql-server'), 'args' => $translationAPI->__('Field arguments', 'graphql-server'), 'type' => $translationAPI->__('Type to which the field belongs', 'graphql-server'), 'isDeprecated' => $translationAPI->__('Is the field deprecated?', 'graphql-server'), 'deprecationReason' => $translationAPI->__('Why was the field deprecated?', 'graphql-server'), 'extensions' => $translationAPI->__('Custom metadata added to the field (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)', 'graphql-server')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
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
        $field = $resultItem;
        switch ($fieldName) {
            case 'name':
                return $field->getName();
            case 'description':
                return $field->getDescription();
            case 'args':
                return $field->getArgIDs();
            case 'type':
                return $field->getTypeID();
            case 'isDeprecated':
                return $field->isDeprecated();
            case 'deprecationReason':
                return $field->getDeprecationDescription();
            case 'extensions':
                return $field->getExtensions();
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'args':
                return \GraphQLByPoP\GraphQLServer\TypeResolvers\InputValueTypeResolver::class;
            case 'type':
                return \GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}

<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumValueTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
class EnumValueFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\GraphQLByPoP\GraphQLServer\TypeResolvers\EnumValueTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['name', 'description', 'isDeprecated', 'deprecationReason'];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['name' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'description' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'isDeprecated' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, 'deprecationReason' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        $nonNullableFieldNames = ['name', 'isDeprecated'];
        if (\in_array($fieldName, $nonNullableFieldNames)) {
            return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['name' => $translationAPI->__('Enum value\'s name as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACvBBCyBH6rd)', 'graphql-server'), 'description' => $translationAPI->__('Enum value\'s description as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACyBIC1BHnjL)', 'graphql-server'), 'isDeprecated' => $translationAPI->__('Is the enum value deprecated?', 'graphql-server'), 'deprecationReason' => $translationAPI->__('Why was the enum value deprecated?', 'graphql-server')];
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
        $enumValue = $resultItem;
        switch ($fieldName) {
            case 'name':
                return $enumValue->getName();
            case 'description':
                return $enumValue->getDescription();
            case 'isDeprecated':
                return $enumValue->isDeprecated();
            case 'deprecationReason':
                return $enumValue->getDeprecatedReason();
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use GraphQLByPoP\GraphQLServer\Enums\TypeKindEnum;
use GraphQLByPoP\GraphQLServer\ObjectModels\EnumType;
use GraphQLByPoP\GraphQLServer\ObjectModels\InputObjectType;
use PoP\ComponentModel\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\FieldTypeResolver;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractNestableType;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasFieldsTypeInterface;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumValueTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\InputValueTypeResolver;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasInterfacesTypeInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasPossibleTypesTypeInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\FieldResolvers\EnumTypeFieldSchemaDefinitionResolverTrait;
class TypeFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    use EnumTypeFieldSchemaDefinitionResolverTrait;
    public static function getClassesToAttachTo() : array
    {
        return array(\GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['kind', 'name', 'description', 'fields', 'interfaces', 'possibleTypes', 'enumValues', 'inputFields', 'ofType', 'extensions'];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['kind' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ENUM, 'name' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'description' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'fields' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID), 'interfaces' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID), 'possibleTypes' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID), 'enumValues' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID), 'inputFields' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID), 'ofType' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, 'extensions' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_MIXED)];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        $nonNullableFieldNames = ['kind'];
        if (\in_array($fieldName, $nonNullableFieldNames)) {
            return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    protected function getSchemaDefinitionEnumName(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'kind':
                /**
                 * @var TypeKindEnum
                 */
                $typeKindEnum = $instanceManager->getInstance(\GraphQLByPoP\GraphQLServer\Enums\TypeKindEnum::class);
                return $typeKindEnum->getName();
        }
        return null;
    }
    protected function getSchemaDefinitionEnumValues(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?array
    {
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'kind':
                /**
                 * @var TypeKindEnum
                 */
                $typeKindEnum = $instanceManager->getInstance(\GraphQLByPoP\GraphQLServer\Enums\TypeKindEnum::class);
                return $typeKindEnum->getValues();
        }
        return null;
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['kind' => $translationAPI->__('Type\'s kind as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACqBBCvBAtrC)', 'graphql-server'), 'name' => $translationAPI->__('Type\'s name as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACvBBCyBH6rd)', 'graphql-server'), 'description' => $translationAPI->__('Type\'s description as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACyBIC1BHnjL)', 'graphql-server'), 'fields' => $translationAPI->__('Type\'s fields (available for Object and Interface types only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLAC3BBCnCA8pY)', 'graphql-server'), 'interfaces' => $translationAPI->__('Type\'s interfaces (available for Object type only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACpCBCxCA7tB)', 'graphql-server'), 'possibleTypes' => $translationAPI->__('Type\'s possible types (available for Interface and Union types only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACzCBC7CA0vN)', 'graphql-server'), 'enumValues' => $translationAPI->__('Type\'s enum values (available for Enum type only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLAC9CDD_CAA2lB)', 'graphql-server'), 'inputFields' => $translationAPI->__('Type\'s input Fields (available for InputObject type only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-HAJbLAuDABCBIu9N)', 'graphql-server'), 'ofType' => $translationAPI->__('The type of the nested type (available for NonNull and List types only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-HAJbLA4DABCBIu9N)', 'graphql-server'), 'extensions' => $translationAPI->__('Custom metadata added to the field (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)', 'graphql-server')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'fields':
            case 'enumValues':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'includeDeprecated', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Include deprecated fields?', 'graphql-server'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEFAULT_VALUE => \false]]);
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
        $type = $resultItem;
        switch ($fieldName) {
            case 'kind':
                return $type->getKind();
            case 'name':
                return $type->getName();
            case 'description':
                return $type->getDescription();
            case 'fields':
                // From GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLAC1BJC3BAn6e):
                // "should be non-null for OBJECT and INTERFACE only, must be null for the others"
                if ($type instanceof \GraphQLByPoP\GraphQLServer\ObjectModels\HasFieldsTypeInterface) {
                    return $type->getFieldIDs($fieldArgs['includeDeprecated']);
                }
                return null;
            case 'interfaces':
                // From GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACnCCCpCA4yV):
                // "should be non-null for OBJECT only, must be null for the others"
                if ($type instanceof \GraphQLByPoP\GraphQLServer\ObjectModels\HasInterfacesTypeInterface) {
                    return $type->getInterfaceIDs();
                }
                return null;
            case 'possibleTypes':
                // From GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACxCCCzCA_9R):
                // "should be non-null for INTERFACE and UNION only, always null for the others"
                if ($type instanceof \GraphQLByPoP\GraphQLServer\ObjectModels\HasPossibleTypesTypeInterface) {
                    return $type->getPossibleTypeIDs();
                }
                return null;
            case 'enumValues':
                // From GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLAC7CCC9CA2nT):
                // "should be non-null for ENUM only, must be null for the others"
                if ($type instanceof \GraphQLByPoP\GraphQLServer\ObjectModels\EnumType) {
                    return $type->getEnumValueIDs($fieldArgs['includeDeprecated']);
                }
                return null;
            case 'inputFields':
                // From GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-HAJbLAuDABCBIu9N):
                // "should be non-null for INPUT_OBJECT only, must be null for the others"
                if ($type instanceof \GraphQLByPoP\GraphQLServer\ObjectModels\InputObjectType) {
                    return $type->getInputFieldIDs();
                }
                return null;
            case 'ofType':
                // From GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-HAJbLA4DABCBIu9N):
                // "should be non-null for NON_NULL and LIST only, must be null for the others"
                if ($type instanceof \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractNestableType) {
                    return $type->getNestedTypeID();
                }
                return null;
            case 'extensions':
                return $type->getExtensions();
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'fields':
                return \GraphQLByPoP\GraphQLServer\TypeResolvers\FieldTypeResolver::class;
            case 'interfaces':
            case 'possibleTypes':
            case 'ofType':
                return \GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver::class;
            case 'enumValues':
                return \GraphQLByPoP\GraphQLServer\TypeResolvers\EnumValueTypeResolver::class;
            case 'inputFields':
                return \GraphQLByPoP\GraphQLServer\TypeResolvers\InputValueTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}

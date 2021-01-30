<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use GraphQLByPoP\GraphQLServer\Enums\DirectiveLocationEnum;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use GraphQLByPoP\GraphQLServer\TypeResolvers\DirectiveTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\InputValueTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\FieldResolvers\EnumTypeFieldSchemaDefinitionResolverTrait;
class DirectiveFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    use EnumTypeFieldSchemaDefinitionResolverTrait;
    public static function getClassesToAttachTo() : array
    {
        return array(\GraphQLByPoP\GraphQLServer\TypeResolvers\DirectiveTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['name', 'description', 'args', 'locations', 'isRepeatable'];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['name' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'description' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'args' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID), 'locations' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ENUM, 'locations' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        $nonNullableFieldNames = ['name', 'args', 'locations', 'isRepeatable'];
        if (\in_array($fieldName, $nonNullableFieldNames)) {
            return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    protected function getSchemaDefinitionEnumName(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'locations':
                /**
                 * @var DirectiveLocationEnum
                 */
                $directiveLocationEnum = $instanceManager->getInstance(\GraphQLByPoP\GraphQLServer\Enums\DirectiveLocationEnum::class);
                return $directiveLocationEnum->getName();
        }
        return null;
    }
    protected function getSchemaDefinitionEnumValues(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?array
    {
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'locations':
                /**
                 * @var DirectiveLocationEnum
                 */
                $directiveLocationEnum = $instanceManager->getInstance(\GraphQLByPoP\GraphQLServer\Enums\DirectiveLocationEnum::class);
                return $directiveLocationEnum->getValues();
        }
        return null;
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['name' => $translationAPI->__('Directive\'s name', 'graphql-server'), 'description' => $translationAPI->__('Directive\'s description', 'graphql-server'), 'args' => $translationAPI->__('Directive\'s arguments', 'graphql-server'), 'locations' => $translationAPI->__('The locations where the directive may be placed', 'graphql-server'), 'isRepeatable' => $translationAPI->__('Can the directive be executed more than once in the same field?', 'graphql-server')];
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
        $directive = $resultItem;
        switch ($fieldName) {
            case 'name':
                return $directive->getName();
            case 'description':
                return $directive->getDescription();
            case 'args':
                return $directive->getArgIDs();
            case 'locations':
                return $directive->getLocations();
            case 'isRepeatable':
                return $directive->isRepeatable();
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'args':
                return \GraphQLByPoP\GraphQLServer\TypeResolvers\InputValueTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}

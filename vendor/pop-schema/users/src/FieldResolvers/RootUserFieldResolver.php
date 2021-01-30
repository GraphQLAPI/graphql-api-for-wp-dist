<?php

declare (strict_types=1);
namespace PoPSchema\Users\FieldResolvers;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\Users\FieldResolvers\AbstractUserFieldResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
class RootUserFieldResolver extends \PoPSchema\Users\FieldResolvers\AbstractUserFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoP\Engine\TypeResolvers\RootTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return \array_merge(parent::getFieldNamesToResolve(), ['user']);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['user' => $translationAPI->__('User with a specific ID', 'pop-users'), 'users' => $translationAPI->__('Users in the current site', 'pop-users')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['user' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'user':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'id', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The user ID', 'pop-users'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
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
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        switch ($fieldName) {
            case 'user':
                $query = ['include' => [$fieldArgs['id']]];
                $options = ['return-type' => \PoPSchema\SchemaCommons\DataLoading\ReturnTypes::IDS];
                if ($users = $cmsusersapi->getUsers($query, $options)) {
                    return $users[0];
                }
                return null;
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'user':
                return \PoPSchema\Users\TypeResolvers\UserTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}

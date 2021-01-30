<?php

declare (strict_types=1);
namespace PoPSchema\UserRoles\FieldResolvers;

use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\UserRoles\Facades\UserRoleTypeDataResolverFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
class UserFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoPSchema\Users\TypeResolvers\UserTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['roles', 'capabilities'];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['roles' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING), 'capabilities' => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING)];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        $nonNullableFieldNames = ['roles', 'capabilities'];
        if (\in_array($fieldName, $nonNullableFieldNames)) {
            return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['roles' => $translationAPI->__('User roles', 'user-roles'), 'capabilities' => $translationAPI->__('User capabilities', 'user-roles')];
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
        $userRoleTypeDataResolver = \PoPSchema\UserRoles\Facades\UserRoleTypeDataResolverFacade::getInstance();
        $user = $resultItem;
        switch ($fieldName) {
            case 'roles':
                return $userRoleTypeDataResolver->getUserRoles($user);
            case 'capabilities':
                return $userRoleTypeDataResolver->getUserCapabilities($user);
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

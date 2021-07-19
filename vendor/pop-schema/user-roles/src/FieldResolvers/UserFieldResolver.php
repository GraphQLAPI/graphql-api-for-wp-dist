<?php

declare (strict_types=1);
namespace PoPSchema\UserRoles\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\UserRoles\Facades\UserRoleTypeDataResolverFacade;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
class UserFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo() : array
    {
        return array(UserTypeResolver::class);
    }
    public function getFieldNamesToResolve() : array
    {
        return ['roles', 'capabilities'];
    }
    public function getAdminFieldNames() : array
    {
        return ['roles', 'capabilities'];
    }
    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName) : string
    {
        $types = ['roles' => SchemaDefinition::TYPE_STRING, 'capabilities' => SchemaDefinition::TYPE_STRING];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName) : ?int
    {
        switch ($fieldName) {
            case 'roles':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            case 'capabilities':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY;
            default:
                return parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName);
        }
    }
    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $descriptions = ['roles' => $this->translationAPI->__('User roles', 'user-roles'), 'capabilities' => $this->translationAPI->__('User capabilities', 'user-roles')];
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
    public function resolveValue(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $userRoleTypeDataResolver = UserRoleTypeDataResolverFacade::getInstance();
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

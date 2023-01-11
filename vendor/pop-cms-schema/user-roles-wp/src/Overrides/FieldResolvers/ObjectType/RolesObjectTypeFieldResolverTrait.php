<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesWP\Overrides\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver;

trait RolesObjectTypeFieldResolverTrait
{
    abstract protected function getUserRoleObjectTypeResolver(): UserRoleObjectTypeResolver;

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'roles':
                return $this->getUserRoleObjectTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
}

<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\UserRolesWP\TypeDataLoaders\UserRoleTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

class UserRoleTypeResolver extends AbstractTypeResolver
{
    public const NAME = 'UserRole';

    public function getTypeName(): string
    {
        return self::NAME;
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('User roles', 'user-roles');
    }

    /**
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $role = $resultItem;
        return $role->name;
    }

    public function getTypeDataLoaderClass(): string
    {
        return UserRoleTypeDataLoader::class;
    }
}

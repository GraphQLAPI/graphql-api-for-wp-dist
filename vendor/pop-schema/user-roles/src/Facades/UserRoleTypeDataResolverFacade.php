<?php

declare (strict_types=1);
namespace PoPSchema\UserRoles\Facades;

use PoPSchema\UserRoles\TypeDataResolvers\UserRoleTypeDataResolverInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class UserRoleTypeDataResolverFacade
{
    public static function getInstance() : \PoPSchema\UserRoles\TypeDataResolvers\UserRoleTypeDataResolverInterface
    {
        /**
         * @var UserRoleTypeDataResolverInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\UserRoles\TypeDataResolvers\UserRoleTypeDataResolverInterface::class);
        return $service;
    }
}

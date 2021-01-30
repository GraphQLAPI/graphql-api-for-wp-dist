<?php

declare (strict_types=1);
namespace PoPSchema\UserState\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\UserState\TypeDataResolvers\UserStateTypeDataResolverInterface;
class UserStateTypeDataResolverFacade
{
    public static function getInstance() : \PoPSchema\UserState\TypeDataResolvers\UserStateTypeDataResolverInterface
    {
        /**
         * @var UserStateTypeDataResolverInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\UserState\TypeDataResolvers\UserStateTypeDataResolverInterface::class);
        return $service;
    }
}

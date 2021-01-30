<?php

declare (strict_types=1);
namespace PoPSchema\UserStateMutations\Facades;

use PoPSchema\UserStateMutations\TypeAPIs\UserStateTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class UserStateTypeAPIFacade
{
    public static function getInstance() : \PoPSchema\UserStateMutations\TypeAPIs\UserStateTypeAPIInterface
    {
        /**
         * @var UserStateTypeAPIInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\UserStateMutations\TypeAPIs\UserStateTypeAPIInterface::class);
        return $service;
    }
}

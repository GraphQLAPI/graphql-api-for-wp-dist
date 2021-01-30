<?php

declare (strict_types=1);
namespace PoPSchema\Users\Facades;

use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class UserTypeAPIFacade
{
    public static function getInstance() : \PoPSchema\Users\TypeAPIs\UserTypeAPIInterface
    {
        /**
         * @var UserTypeAPIInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\Users\TypeAPIs\UserTypeAPIInterface::class);
        return $service;
    }
}

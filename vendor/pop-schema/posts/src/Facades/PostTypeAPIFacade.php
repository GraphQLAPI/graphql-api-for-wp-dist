<?php

declare (strict_types=1);
namespace PoPSchema\Posts\Facades;

use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class PostTypeAPIFacade
{
    public static function getInstance() : \PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface
    {
        /**
         * @var PostTypeAPIInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface::class);
        return $service;
    }
}

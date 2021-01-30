<?php

declare (strict_types=1);
namespace PoPSchema\Tags\Facades;

use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class TagTypeAPIFacade
{
    public static function getInstance() : \PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface
    {
        /**
         * @var TagTypeAPIInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface::class);
        return $service;
    }
}

<?php

declare (strict_types=1);
namespace PoPSchema\PostTags\Facades;

use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class PostTagTypeAPIFacade
{
    public static function getInstance() : \PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface
    {
        /**
         * @var PostTagTypeAPIInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface::class);
        return $service;
    }
}

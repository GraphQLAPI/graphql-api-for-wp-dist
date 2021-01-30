<?php

declare (strict_types=1);
namespace PoPSchema\CustomPosts\Facades;

use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class CustomPostTypeAPIFacade
{
    public static function getInstance() : \PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface
    {
        /**
         * @var CustomPostTypeAPIInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface::class);
        return $service;
    }
}

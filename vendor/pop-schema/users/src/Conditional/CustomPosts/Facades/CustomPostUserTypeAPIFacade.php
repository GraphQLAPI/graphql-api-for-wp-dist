<?php

declare (strict_types=1);
namespace PoPSchema\Users\Conditional\CustomPosts\Facades;

use PoPSchema\Users\Conditional\CustomPosts\TypeAPIs\CustomPostUserTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class CustomPostUserTypeAPIFacade
{
    public static function getInstance() : \PoPSchema\Users\Conditional\CustomPosts\TypeAPIs\CustomPostUserTypeAPIInterface
    {
        /**
         * @var CustomPostUserTypeAPIInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\Users\Conditional\CustomPosts\TypeAPIs\CustomPostUserTypeAPIInterface::class);
        return $service;
    }
}

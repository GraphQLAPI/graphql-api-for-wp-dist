<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMutations\Facades;

use PoPSchema\CustomPostMutations\TypeAPIs\CustomPostTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class CustomPostTypeAPIFacade
{
    public static function getInstance() : \PoPSchema\CustomPostMutations\TypeAPIs\CustomPostTypeAPIInterface
    {
        /**
         * @var CustomPostTypeAPIInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\CustomPostMutations\TypeAPIs\CustomPostTypeAPIInterface::class);
        return $service;
    }
}

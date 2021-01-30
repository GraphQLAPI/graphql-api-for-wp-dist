<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMediaMutations\Facades;

use PoPSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class CustomPostMediaTypeAPIFacade
{
    public static function getInstance() : \PoPSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeAPIInterface
    {
        /**
         * @var CustomPostMediaTypeAPIInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeAPIInterface::class);
        return $service;
    }
}

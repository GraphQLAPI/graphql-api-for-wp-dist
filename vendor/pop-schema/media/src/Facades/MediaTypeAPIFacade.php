<?php

declare (strict_types=1);
namespace PoPSchema\Media\Facades;

use PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class MediaTypeAPIFacade
{
    public static function getInstance() : \PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface
    {
        /**
         * @var MediaTypeAPIInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface::class);
        return $service;
    }
}

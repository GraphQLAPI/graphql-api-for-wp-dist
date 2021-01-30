<?php

declare (strict_types=1);
namespace PoPSchema\Pages\Facades;

use PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class PageTypeAPIFacade
{
    public static function getInstance() : \PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface
    {
        /**
         * @var PageTypeAPIInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface::class);
        return $service;
    }
}

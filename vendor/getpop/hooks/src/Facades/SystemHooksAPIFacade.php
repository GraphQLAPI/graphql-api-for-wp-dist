<?php

declare (strict_types=1);
namespace PoP\Hooks\Facades;

use PoP\Hooks\HooksAPIInterface;
use PoP\Root\Container\SystemContainerBuilderFactory;
class SystemHooksAPIFacade
{
    public static function getInstance() : \PoP\Hooks\HooksAPIInterface
    {
        /**
         * @var HooksAPIInterface
         */
        $service = \PoP\Root\Container\SystemContainerBuilderFactory::getInstance()->get(\PoP\Hooks\HooksAPIInterface::class);
        return $service;
    }
}

<?php

declare (strict_types=1);
namespace PoP\Translation\Facades;

use PoP\Translation\TranslationAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class TranslationAPIFacade
{
    public static function getInstance() : \PoP\Translation\TranslationAPIInterface
    {
        /**
         * @var TranslationAPIInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\Translation\TranslationAPIInterface::class);
        return $service;
    }
}

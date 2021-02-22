<?php

declare (strict_types=1);
namespace PoP\Translation\Facades;

use PoP\Translation\TranslationAPIInterface;
use PoP\Root\Container\SystemContainerBuilderFactory;
class SystemTranslationAPIFacade
{
    public static function getInstance() : \PoP\Translation\TranslationAPIInterface
    {
        /**
         * @var TranslationAPIInterface
         */
        $service = \PoP\Root\Container\SystemContainerBuilderFactory::getInstance()->get(\PoP\Translation\TranslationAPIInterface::class);
        return $service;
    }
}

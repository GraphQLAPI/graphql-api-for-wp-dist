<?php

declare (strict_types=1);
namespace PoP\CacheControl\TypeResolverDecorators;

use PoP\CacheControl\Facades\CacheControlManagerFacade;
use PoP\CacheControl\TypeResolverDecorators\ConfigurableCacheControlTypeResolverDecoratorTrait;
use PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators\AbstractMandatoryDirectivesForDirectivesTypeResolverDecorator;
class ConfigurableCacheControlForDirectivesTypeResolverDecorator extends \PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators\AbstractMandatoryDirectivesForDirectivesTypeResolverDecorator
{
    use ConfigurableCacheControlTypeResolverDecoratorTrait;
    protected function getConfigurationEntries() : array
    {
        $cacheControlManager = \PoP\CacheControl\Facades\CacheControlManagerFacade::getInstance();
        return $cacheControlManager->getEntriesForDirectives();
    }
}

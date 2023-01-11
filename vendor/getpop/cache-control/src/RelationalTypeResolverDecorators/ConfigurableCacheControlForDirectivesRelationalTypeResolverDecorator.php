<?php

declare (strict_types=1);
namespace PoP\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\DirectiveResolvers\CacheControlFieldDirectiveResolver;
use PoP\CacheControl\Managers\CacheControlManagerInterface;
use PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators\AbstractMandatoryDirectivesForDirectivesRelationalTypeResolverDecorator;
class ConfigurableCacheControlForDirectivesRelationalTypeResolverDecorator extends AbstractMandatoryDirectivesForDirectivesRelationalTypeResolverDecorator
{
    use \PoP\CacheControl\RelationalTypeResolverDecorators\ConfigurableCacheControlRelationalTypeResolverDecoratorTrait;
    /**
     * @var \PoP\CacheControl\Managers\CacheControlManagerInterface|null
     */
    private $cacheControlManager;
    /**
     * @var \PoP\CacheControl\DirectiveResolvers\CacheControlFieldDirectiveResolver|null
     */
    private $cacheControlFieldDirectiveResolver;
    /**
     * @param \PoP\CacheControl\Managers\CacheControlManagerInterface $cacheControlManager
     */
    public final function setCacheControlManager($cacheControlManager) : void
    {
        $this->cacheControlManager = $cacheControlManager;
    }
    protected final function getCacheControlManager() : CacheControlManagerInterface
    {
        /** @var CacheControlManagerInterface */
        return $this->cacheControlManager = $this->cacheControlManager ?? $this->instanceManager->getInstance(CacheControlManagerInterface::class);
    }
    /**
     * @param \PoP\CacheControl\DirectiveResolvers\CacheControlFieldDirectiveResolver $cacheControlFieldDirectiveResolver
     */
    public final function setCacheControlFieldDirectiveResolver($cacheControlFieldDirectiveResolver) : void
    {
        $this->cacheControlFieldDirectiveResolver = $cacheControlFieldDirectiveResolver;
    }
    protected final function getCacheControlFieldDirectiveResolver() : CacheControlFieldDirectiveResolver
    {
        /** @var CacheControlFieldDirectiveResolver */
        return $this->cacheControlFieldDirectiveResolver = $this->cacheControlFieldDirectiveResolver ?? $this->instanceManager->getInstance(CacheControlFieldDirectiveResolver::class);
    }
    /**
     * @return array<mixed[]>
     */
    protected function getConfigurationEntries() : array
    {
        return $this->getCacheControlManager()->getEntriesForDirectives();
    }
}

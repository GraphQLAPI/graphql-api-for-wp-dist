<?php

declare (strict_types=1);
namespace PoP\ComponentModel\RelationalTypeDataLoaders;

use PoP\Root\Services\BasicServiceTrait;
use PoP\LooseContracts\NameResolverInterface;
abstract class AbstractRelationalTypeDataLoader implements \PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface
{
    use BasicServiceTrait;
    /**
     * @var \PoP\LooseContracts\NameResolverInterface|null
     */
    private $nameResolver;
    /**
     * @param \PoP\LooseContracts\NameResolverInterface $nameResolver
     */
    public final function setNameResolver($nameResolver) : void
    {
        $this->nameResolver = $nameResolver;
    }
    protected final function getNameResolver() : NameResolverInterface
    {
        /** @var NameResolverInterface */
        return $this->nameResolver = $this->nameResolver ?? $this->instanceManager->getInstance(NameResolverInterface::class);
    }
}

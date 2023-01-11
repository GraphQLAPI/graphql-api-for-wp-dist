<?php

declare (strict_types=1);
namespace PoP\LooseContracts;

use PoP\Root\Services\BasicServiceTrait;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
abstract class AbstractLooseContractResolutionSet extends AbstractAutomaticallyInstantiatedService
{
    use BasicServiceTrait;
    /**
     * @var \PoP\LooseContracts\LooseContractManagerInterface|null
     */
    private $looseContractManager;
    /**
     * @var \PoP\LooseContracts\NameResolverInterface|null
     */
    private $nameResolver;
    /**
     * @param \PoP\LooseContracts\LooseContractManagerInterface $looseContractManager
     */
    public final function setLooseContractManager($looseContractManager) : void
    {
        $this->looseContractManager = $looseContractManager;
    }
    protected final function getLooseContractManager() : \PoP\LooseContracts\LooseContractManagerInterface
    {
        /** @var LooseContractManagerInterface */
        return $this->looseContractManager = $this->looseContractManager ?? $this->instanceManager->getInstance(\PoP\LooseContracts\LooseContractManagerInterface::class);
    }
    /**
     * @param \PoP\LooseContracts\NameResolverInterface $nameResolver
     */
    public final function setNameResolver($nameResolver) : void
    {
        $this->nameResolver = $nameResolver;
    }
    protected final function getNameResolver() : \PoP\LooseContracts\NameResolverInterface
    {
        /** @var NameResolverInterface */
        return $this->nameResolver = $this->nameResolver ?? $this->instanceManager->getInstance(\PoP\LooseContracts\NameResolverInterface::class);
    }
    public final function initialize() : void
    {
        $this->resolveContracts();
    }
    /**
     * Function to execute all code to satisfy the contracts
     */
    protected abstract function resolveContracts() : void;
}

<?php

declare (strict_types=1);
namespace PoP\LooseContracts;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\WithInstanceManagerServiceTrait;
abstract class AbstractLooseContractSet extends AbstractAutomaticallyInstantiatedService
{
    use WithInstanceManagerServiceTrait;
    /**
     * @var \PoP\LooseContracts\LooseContractManagerInterface|null
     */
    private $looseContractManager;
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
    public function initialize() : void
    {
        $this->getLooseContractManager()->requireNames($this->getRequiredNames());
    }
    /**
     * @return string[]
     */
    public function getRequiredNames() : array
    {
        return [];
    }
}

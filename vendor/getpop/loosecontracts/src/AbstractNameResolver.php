<?php

declare (strict_types=1);
namespace PoP\LooseContracts;

use PoP\Root\Services\WithInstanceManagerServiceTrait;
abstract class AbstractNameResolver implements \PoP\LooseContracts\NameResolverInterface
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
    /**
     * @param string $abstractName
     * @param string $implementationName
     */
    public function implementName($abstractName, $implementationName) : void
    {
        $this->getLooseContractManager()->implementNames([$abstractName]);
    }
    /**
     * @param string[] $names
     */
    public function implementNames($names) : void
    {
        $this->getLooseContractManager()->implementNames(\array_keys($names));
    }
}

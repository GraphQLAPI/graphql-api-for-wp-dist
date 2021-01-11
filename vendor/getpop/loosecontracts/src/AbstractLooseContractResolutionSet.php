<?php

declare(strict_types=1);

namespace PoP\LooseContracts;

use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\LooseContracts\LooseContractManagerInterface;

abstract class AbstractLooseContractResolutionSet
{
    /**
     * @var \PoP\LooseContracts\LooseContractManagerInterface
     */
    protected $looseContractManager;
    /**
     * @var \PoP\LooseContracts\NameResolverInterface
     */
    protected $nameResolver;
    /**
     * @var \PoP\Hooks\HooksAPIInterface
     */
    protected $hooksAPI;

    public function __construct(
        LooseContractManagerInterface $looseContractManager,
        NameResolverInterface $nameResolver,
        HooksAPIInterface $hooksAPI
    ) {
        $this->looseContractManager = $looseContractManager;
        $this->nameResolver = $nameResolver;
        $this->hooksAPI = $hooksAPI;

        $this->resolveContracts();
    }
    /**
     * Function to execute all code to satisfy the contracts
     *
     * @return void
     */
    abstract protected function resolveContracts();
}

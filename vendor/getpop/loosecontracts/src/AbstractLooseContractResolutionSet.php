<?php

declare (strict_types=1);
namespace PoP\LooseContracts;

use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\LooseContractManagerInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
abstract class AbstractLooseContractResolutionSet extends \PoP\Root\Services\AbstractAutomaticallyInstantiatedService
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
    public function __construct(\PoP\LooseContracts\LooseContractManagerInterface $looseContractManager, \PoP\LooseContracts\NameResolverInterface $nameResolver, \PoP\Hooks\HooksAPIInterface $hooksAPI)
    {
        $this->looseContractManager = $looseContractManager;
        $this->nameResolver = $nameResolver;
        $this->hooksAPI = $hooksAPI;
    }
    public final function initialize() : void
    {
        $this->resolveContracts();
    }
    /**
     * Function to execute all code to satisfy the contracts
     *
     * @return void
     */
    protected abstract function resolveContracts();
}

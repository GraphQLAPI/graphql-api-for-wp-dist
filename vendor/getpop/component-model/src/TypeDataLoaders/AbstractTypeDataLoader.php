<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeDataLoaders;

use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
abstract class AbstractTypeDataLoader implements \PoP\ComponentModel\TypeDataLoaders\TypeDataLoaderInterface
{
    /**
     * @var \PoP\Hooks\HooksAPIInterface
     */
    protected $hooksAPI;
    /**
     * @var \PoP\ComponentModel\Instances\InstanceManagerInterface
     */
    protected $instanceManager;
    /**
     * @var \PoP\LooseContracts\NameResolverInterface
     */
    protected $nameResolver;
    public function __construct(HooksAPIInterface $hooksAPI, InstanceManagerInterface $instanceManager, NameResolverInterface $nameResolver)
    {
        $this->hooksAPI = $hooksAPI;
        $this->instanceManager = $instanceManager;
        $this->nameResolver = $nameResolver;
    }
}

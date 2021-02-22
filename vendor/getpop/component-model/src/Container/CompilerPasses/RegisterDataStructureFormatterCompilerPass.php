<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\DataStructure\DataStructureFormatterInterface;
use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;
class RegisterDataStructureFormatterCompilerPass extends \PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition() : string
    {
        return \PoP\ComponentModel\DataStructure\DataStructureManagerInterface::class;
    }
    protected function getServiceClass() : string
    {
        return \PoP\ComponentModel\DataStructure\DataStructureFormatterInterface::class;
    }
    protected function getRegistryMethodCallName() : string
    {
        return 'addDataStructureFormatter';
    }
}

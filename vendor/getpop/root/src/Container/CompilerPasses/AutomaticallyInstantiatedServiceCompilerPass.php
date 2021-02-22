<?php

declare (strict_types=1);
namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Services\AutomaticallyInstantiatedServiceInterface;
class AutomaticallyInstantiatedServiceCompilerPass extends \PoP\Root\Container\CompilerPasses\AbstractInstantiateServiceCompilerPass
{
    protected function getServiceClass() : string
    {
        return \PoP\Root\Services\AutomaticallyInstantiatedServiceInterface::class;
    }
}

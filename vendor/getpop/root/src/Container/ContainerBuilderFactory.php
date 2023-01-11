<?php

declare (strict_types=1);
namespace PoP\Root\Container;

class ContainerBuilderFactory
{
    use \PoP\Root\Container\ContainerBuilderFactoryTrait;
    public function getContainerClassName() : string
    {
        return 'ApplicationServiceContainer';
    }
}

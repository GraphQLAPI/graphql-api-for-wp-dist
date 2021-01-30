<?php

declare (strict_types=1);
namespace PoP\Engine\ObjectFacades;

use PoP\Engine\ObjectModels\Root;
use PoP\Root\Container\ContainerBuilderFactory;
class RootObjectFacade
{
    public static function getInstance() : \PoP\Engine\ObjectModels\Root
    {
        $containerBuilderFactory = \PoP\Root\Container\ContainerBuilderFactory::getInstance();
        return $containerBuilderFactory->get(\PoP\Engine\ObjectModels\Root::class);
    }
}

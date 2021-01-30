<?php

declare (strict_types=1);
namespace PoPSchema\Tags\ComponentContracts;

trait TagAPIRequestedContractTrait
{
    protected abstract function getTypeAPI() : \PoPSchema\Tags\FunctionAPI;
    protected abstract function getTypeResolverClass() : string;
    protected function getObjectPropertyAPI() : \PoPSchema\Tags\ObjectPropertyResolver
    {
        $cmstagsresolver = \PoPSchema\Tags\ObjectPropertyResolverFactory::getInstance();
        return $cmstagsresolver;
    }
}

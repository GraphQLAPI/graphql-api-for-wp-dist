<?php

namespace PoPSchema\Tags;

abstract class ObjectPropertyResolver_Base implements \PoPSchema\Tags\ObjectPropertyResolver
{
    public function __construct()
    {
        \PoPSchema\Tags\ObjectPropertyResolverFactory::setInstance($this);
    }
}

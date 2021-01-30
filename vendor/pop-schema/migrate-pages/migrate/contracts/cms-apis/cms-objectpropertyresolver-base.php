<?php

namespace PoPSchema\Pages;

abstract class ObjectPropertyResolver_Base implements \PoPSchema\Pages\ObjectPropertyResolver
{
    public function __construct()
    {
        \PoPSchema\Pages\ObjectPropertyResolverFactory::setInstance($this);
    }
}

<?php

namespace PoPSchema\Users;

abstract class ObjectPropertyResolver_Base implements \PoPSchema\Users\ObjectPropertyResolver
{
    public function __construct()
    {
        \PoPSchema\Users\ObjectPropertyResolverFactory::setInstance($this);
    }
}

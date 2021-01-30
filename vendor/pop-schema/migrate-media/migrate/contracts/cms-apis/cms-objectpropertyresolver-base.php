<?php

namespace PoPSchema\Media;

abstract class ObjectPropertyResolver_Base implements \PoPSchema\Media\ObjectPropertyResolver
{
    public function __construct()
    {
        \PoPSchema\Media\ObjectPropertyResolverFactory::setInstance($this);
    }
}

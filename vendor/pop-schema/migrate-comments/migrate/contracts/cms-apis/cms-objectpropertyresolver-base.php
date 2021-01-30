<?php

namespace PoPSchema\Comments;

abstract class ObjectPropertyResolver_Base implements \PoPSchema\Comments\ObjectPropertyResolver
{
    public function __construct()
    {
        \PoPSchema\Comments\ObjectPropertyResolverFactory::setInstance($this);
    }
}

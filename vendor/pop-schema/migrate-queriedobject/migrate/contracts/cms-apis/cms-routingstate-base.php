<?php

namespace PoPSchema\QueriedObject;

abstract class AbstractCMSRoutingState implements \PoPSchema\QueriedObject\CMSRoutingStateInterface
{
    public function __construct()
    {
        \PoPSchema\QueriedObject\CMSRoutingStateFactory::setInstance($this);
    }
}

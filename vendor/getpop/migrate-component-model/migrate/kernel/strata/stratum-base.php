<?php

namespace PoP\ComponentModel;

abstract class StratumBase
{
    public function __construct()
    {
        $stratummanager = \PoP\ComponentModel\StratumManagerFactory::getInstance();
        $stratummanager->add($this->getStratum(), $this->getStrata());
    }
    public abstract function getStratum();
    public abstract function getStrata();
}

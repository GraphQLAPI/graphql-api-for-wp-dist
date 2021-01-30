<?php

namespace PoP\ComponentModel;

abstract class AbstractFilterInputProcessor implements \PoP\ComponentModel\FilterInput
{
    public function getFilterInputsToProcess()
    {
        return array();
    }
}

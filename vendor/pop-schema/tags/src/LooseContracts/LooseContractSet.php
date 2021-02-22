<?php

declare (strict_types=1);
namespace PoPSchema\Tags\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractSet;
class LooseContractSet extends \PoP\LooseContracts\AbstractLooseContractSet
{
    /**
     * @return string[]
     */
    public function getRequiredNames() : array
    {
        return [
            // DB Columns
            'popcms:dbcolumn:orderby:tags:count',
            'popcms:dbcolumn:orderby:tags:id',
        ];
    }
}

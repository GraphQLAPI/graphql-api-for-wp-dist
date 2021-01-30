<?php

namespace PoPSchema\Tags;

use PoP\LooseContracts\AbstractLooseContractSet;
class CMSLooseContracts extends \PoP\LooseContracts\AbstractLooseContractSet
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
/**
 * Initialize
 */
new \PoPSchema\Tags\CMSLooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

<?php

namespace PoPSchema\Pages;

use PoP\LooseContracts\AbstractLooseContractSet;
class CMSLooseContracts extends \PoP\LooseContracts\AbstractLooseContractSet
{
    /**
     * @return string[]
     */
    public function getRequiredHooks() : array
    {
        return [
            // Filters
            'popcms:page:title',
        ];
    }
    /**
     * @return string[]
     */
    public function getRequiredNames() : array
    {
        return [
            // DB Columns
            'popcms:dbcolumn:orderby:pages:date',
        ];
    }
}
/**
 * Initialize
 */
new \PoPSchema\Pages\CMSLooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

<?php

namespace PoPSchema\Posts;

use PoP\LooseContracts\AbstractLooseContractSet;
class CMSLooseContracts extends \PoP\LooseContracts\AbstractLooseContractSet
{
    /**
     * @return string[]
     */
    public function getRequiredHooks() : array
    {
        return [
            // Actions
            'popcms:post:title',
            'popcms:excerptMore',
        ];
    }
    /**
     * @return string[]
     */
    public function getRequiredNames() : array
    {
        return [
            // DB Columns
            'popcms:dbcolumn:orderby:customposts:date',
            'popcms:dbcolumn:orderby:customposts:modified',
            'popcms:dbcolumn:orderby:customposts:id',
        ];
    }
}
/**
 * Initialize
 */
new \PoPSchema\Posts\CMSLooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

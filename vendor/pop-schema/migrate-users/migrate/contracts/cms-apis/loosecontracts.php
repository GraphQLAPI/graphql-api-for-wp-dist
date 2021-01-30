<?php

namespace PoPSchema\Users;

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
            'popcms:dbcolumn:orderby:users:name',
            'popcms:dbcolumn:orderby:users:id',
            'popcms:dbcolumn:orderby:users:registrationdate',
        ];
    }
}
/**
 * Initialize
 */
new \PoPSchema\Users\CMSLooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

<?php

namespace PoPSchema\Comments;

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
            'popcms:insertComment',
            'popcms:spamComment',
            'popcms:deleteComment',
        ];
    }
    /**
     * @return string[]
     */
    public function getRequiredNames() : array
    {
        return [
            // DB Columns
            'popcms:dbcolumn:orderby:comments:date',
            'popcms:dbcolumn:orderby:customposts:comment-count',
        ];
    }
}
/**
 * Initialize
 */
new \PoPSchema\Comments\CMSLooseContracts(\PoP\LooseContracts\Facades\LooseContractManagerFacade::getInstance());

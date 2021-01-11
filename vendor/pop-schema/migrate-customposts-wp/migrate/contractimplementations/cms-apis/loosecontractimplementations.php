<?php
namespace PoPSchema\CustomPosts\WP;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;
use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class CMSLooseContractImplementations extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts()
    {
        // Filters.
        $this->hooksAPI->addFilter('the_title', function ($post_title, $post_id) {
            return $this->hooksAPI->applyFilters('popcms:post:title', $post_title, $post_id);
        }, 10, 2);
        $this->hooksAPI->addFilter('excerpt_more', function ($text) {
            return $this->hooksAPI->applyFilters('popcms:excerptMore', $text);
        }, 10, 1);

        $this->looseContractManager->implementHooks([
            'popcms:post:title',
            'popcms:excerptMore',
        ]);

        $this->nameResolver->implementNames([
            'popcms:dbcolumn:orderby:customposts:date' => 'date',
            'popcms:dbcolumn:orderby:customposts:modified' => 'modified',
            'popcms:dbcolumn:orderby:customposts:id' => 'ID',
        ]);
    }
}

/**
 * Initialize
 */
new CMSLooseContractImplementations(
    LooseContractManagerFacade::getInstance(),
    NameResolverFacade::getInstance(),
    HooksAPIFacade::getInstance()
);

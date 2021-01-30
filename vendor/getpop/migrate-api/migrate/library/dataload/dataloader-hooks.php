<?php

namespace PoP\API;

use PoP\Hooks\Facades\HooksAPIFacade;
class DataloaderHooks
{
    public function __construct()
    {
        \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addFilter('PoP\\ComponentModel\\Engine:moveEntriesUnderDBName:dbName-dataFields', array($this, 'moveEntriesUnderDBName'));
    }
    public function moveEntriesUnderDBName($dbname_datafields)
    {
        // All fields starting with "__" (such as "__schema") are meta
        $dbname_datafields['meta'] = \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('PoP\\API\\DataloaderHooks:metaFields', ['fullSchema', 'typeName']);
        $dbname_datafields['context'] = \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('PoP\\API\\DataloaderHooks:contextFields', ['var', 'context']);
        return $dbname_datafields;
    }
}
/**
 * Initialize
 */
new \PoP\API\DataloaderHooks();

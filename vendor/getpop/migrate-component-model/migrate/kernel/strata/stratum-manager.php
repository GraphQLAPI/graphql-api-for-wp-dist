<?php

namespace PoP\ComponentModel;

use PoP\Hooks\Facades\HooksAPIFacade;
class StratumManager
{
    private $selected_stratum;
    private $stratum_strata = [];
    private $last_registered_stratum;
    public function __construct()
    {
        \PoP\ComponentModel\StratumManagerFactory::setInstance($this);
        \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 395);
    }
    public function add($stratum, $strata)
    {
        $this->stratum_strata[$stratum] = $strata;
        $this->last_registered_stratum = $stratum;
    }
    public function init()
    {
        // Selected comes in URL param 'stratum'
        $this->selected_stratum = $_REQUEST[\PoP\ComponentModel\Constants\Params::STRATUM] ?? null;
        // Check if the selected theme is inside $stratum_strata
        if (!$this->selected_stratum || !\in_array($this->selected_stratum, \array_keys($this->stratum_strata))) {
            $this->selected_stratum = $this->getDefaultStratum();
        }
    }
    public function getDefaultStratum()
    {
        // By default, use the last defined stratum (the highest-level one) as the default
        return \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('Stratum:default', $this->last_registered_stratum);
    }
    public function getStratum()
    {
        return $this->selected_stratum;
    }
    public function getStrata($stratum = null)
    {
        $stratum = $stratum ?? $this->selected_stratum;
        return $this->stratum_strata[$stratum];
    }
    public function isDefaultStratum()
    {
        return $this->selected_stratum == $this->getDefaultStratum();
    }
}
/**
 * Initialization
 */
new \PoP\ComponentModel\StratumManager();

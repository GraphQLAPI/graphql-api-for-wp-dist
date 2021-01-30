<?php

namespace PrefixedByPoP;

class GD_Stratum_Data extends \PoP\ComponentModel\StratumBase
{
    public function getStratum()
    {
        return \PoP\Engine\Constants\Stratum::DATA;
    }
    public function getStrata()
    {
        return [\PoP\Engine\Constants\Stratum::DATA];
    }
}
\class_alias('PrefixedByPoP\\GD_Stratum_Data', 'GD_Stratum_Data', \false);
/**
 * Initialization
 */
new \PrefixedByPoP\GD_Stratum_Data();

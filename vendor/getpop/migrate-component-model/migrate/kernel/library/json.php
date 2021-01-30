<?php

namespace PrefixedByPoP;

use PoP\ComponentModel\State\ApplicationState;
// Returns true if the response format must be in JSON
function doingJson()
{
    $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
    return $vars['output'] == \PoP\ComponentModel\Constants\Outputs::JSON;
    // return isset($_REQUEST[\PoP\ComponentModel\Constants\Params::OUTPUT]) && $_REQUEST[\PoP\ComponentModel\Constants\Params::OUTPUT] == \PoP\ComponentModel\Constants\Outputs::JSON;
}

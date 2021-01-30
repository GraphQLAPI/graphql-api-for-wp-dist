<?php

namespace PoP\API;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Configuration\Request;
use PoP\API\Response\Schemes as APISchemes;
class APIUtils
{
    public static function getEndpoint($url, $dataoutputitems = null) : string
    {
        $dataoutputitems = $dataoutputitems ?? [\PoP\ComponentModel\Constants\DataOutputItems::MODULE_DATA, \PoP\ComponentModel\Constants\DataOutputItems::DATABASES, \PoP\ComponentModel\Constants\DataOutputItems::DATASET_MODULE_SETTINGS];
        $endpoint = \PoP\ComponentModel\Misc\GeneralUtils::addQueryArgs([
            \PoP\ComponentModel\Constants\Params::SCHEME => \PoP\API\Response\Schemes::API,
            \PoP\ComponentModel\Constants\Params::OUTPUT => \PoP\ComponentModel\Constants\Outputs::JSON,
            \PoP\ComponentModel\Constants\Params::DATAOUTPUTMODE => \PoP\ComponentModel\Constants\DataOutputModes::COMBINED,
            // \PoP\ComponentModel\Constants\Params::DATABASESOUTPUTMODE => \PoP\ComponentModel\Constants\DatabasesOutputModes::COMBINED,
            \PoP\ComponentModel\Constants\Params::DATA_OUTPUT_ITEMS => \implode(\PoP\ComponentModel\Tokens\Param::VALUE_SEPARATOR, $dataoutputitems),
        ], $url);
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        if ($mangled = $vars['mangled']) {
            $endpoint = \PoP\ComponentModel\Misc\GeneralUtils::addQueryArgs([\PoP\ComponentModel\Configuration\Request::URLPARAM_MANGLED => $mangled], $endpoint);
        }
        return $endpoint;
    }
}

<?php

declare (strict_types=1);
namespace PoP\ComponentModel\State;

use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Constants\Outputs;
use PoP\ComponentModel\Constants\DataSourceSelectors;
use PoP\ComponentModel\Constants\DataOutputModes;
use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\ComponentModel\Tokens\Param;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\Targets;
use PoP\ComponentModel\Constants\Values;
use PoP\Routing\RouteNatures;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Configuration\Request;
use PoP\Routing\Facades\RoutingManagerFacade;
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\Facades\ModuleFiltering\ModuleFilterManagerFacade;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\StratumManagerFactory;
use PoP\ComponentModel\Environment as Environment;
class ApplicationState
{
    /**
     * @var array<string, mixed>
     */
    public static $vars = [];
    /**
     * @return array<string, mixed>
     */
    public static function getVars() : array
    {
        if (self::$vars) {
            return self::$vars;
        }
        // Only initialize the first time. Then, it will call ->resetState() to retrieve new state, no need to create a new instance
        $routingManager = \PoP\Routing\Facades\RoutingManagerFacade::getInstance();
        $nature = $routingManager->getCurrentNature();
        $route = $routingManager->getCurrentRoute();
        // Convert them to lower to make it insensitive to upper/lower case values
        $output = \strtolower($_REQUEST[\PoP\ComponentModel\Constants\Params::OUTPUT] ?? '');
        $dataoutputitems = $_REQUEST[\PoP\ComponentModel\Constants\Params::DATA_OUTPUT_ITEMS] ?? [];
        $datasources = \strtolower($_REQUEST[\PoP\ComponentModel\Constants\Params::DATA_SOURCE] ?? '');
        $datastructure = \strtolower($_REQUEST[\PoP\ComponentModel\Constants\Params::DATASTRUCTURE] ?? '');
        $dataoutputmode = \strtolower($_REQUEST[\PoP\ComponentModel\Constants\Params::DATAOUTPUTMODE] ?? '');
        $dboutputmode = \strtolower($_REQUEST[\PoP\ComponentModel\Constants\Params::DATABASESOUTPUTMODE] ?? '');
        $target = \strtolower($_REQUEST[\PoP\ComponentModel\Constants\Params::TARGET] ?? '');
        $mangled = \PoP\ComponentModel\Configuration\Request::isMangled() ? '' : \PoP\ComponentModel\Configuration\Request::URLPARAMVALUE_MANGLED_NONE;
        $actions = isset($_REQUEST[\PoP\ComponentModel\Constants\Params::ACTIONS]) ? \array_map('strtolower', $_REQUEST[\PoP\ComponentModel\Constants\Params::ACTIONS]) : [];
        $scheme = \strtolower($_REQUEST[\PoP\ComponentModel\Constants\Params::SCHEME] ?? '');
        // The version could possibly be set from outside
        $version = \PoP\ComponentModel\Environment::enableVersionByParams() ? $_REQUEST[\PoP\ComponentModel\Constants\Params::VERSION] ?? \PoP\ComponentModel\Facades\Info\ApplicationInfoFacade::getInstance()->getVersion() : \PoP\ComponentModel\Facades\Info\ApplicationInfoFacade::getInstance()->getVersion();
        $outputs = (array) \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('ApplicationState:outputs', array(\PoP\ComponentModel\Constants\Outputs::HTML, \PoP\ComponentModel\Constants\Outputs::JSON));
        if (!\in_array($output, $outputs)) {
            $output = \PoP\ComponentModel\Constants\Outputs::HTML;
        }
        // Target/Module default values (for either empty, or if the user is playing around with the url)
        $alldatasources = array(\PoP\ComponentModel\Constants\DataSourceSelectors::ONLYMODEL, \PoP\ComponentModel\Constants\DataSourceSelectors::MODELANDREQUEST);
        if (!\in_array($datasources, $alldatasources)) {
            $datasources = \PoP\ComponentModel\Constants\DataSourceSelectors::MODELANDREQUEST;
        }
        $dataoutputmodes = array(\PoP\ComponentModel\Constants\DataOutputModes::SPLITBYSOURCES, \PoP\ComponentModel\Constants\DataOutputModes::COMBINED);
        if (!\in_array($dataoutputmode, $dataoutputmodes)) {
            $dataoutputmode = \PoP\ComponentModel\Constants\DataOutputModes::SPLITBYSOURCES;
        }
        $dboutputmodes = array(\PoP\ComponentModel\Constants\DatabasesOutputModes::SPLITBYDATABASES, \PoP\ComponentModel\Constants\DatabasesOutputModes::COMBINED);
        if (!\in_array($dboutputmode, $dboutputmodes)) {
            $dboutputmode = \PoP\ComponentModel\Constants\DatabasesOutputModes::SPLITBYDATABASES;
        }
        if ($dataoutputitems) {
            if (!\is_array($dataoutputitems)) {
                $dataoutputitems = \explode(\PoP\ComponentModel\Tokens\Param::VALUE_SEPARATOR, \strtolower($dataoutputitems));
            } else {
                $dataoutputitems = \array_map('strtolower', $dataoutputitems);
            }
        }
        $alldataoutputitems = (array) \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('ApplicationState:dataoutputitems', array(\PoP\ComponentModel\Constants\DataOutputItems::META, \PoP\ComponentModel\Constants\DataOutputItems::DATASET_MODULE_SETTINGS, \PoP\ComponentModel\Constants\DataOutputItems::MODULE_DATA, \PoP\ComponentModel\Constants\DataOutputItems::DATABASES, \PoP\ComponentModel\Constants\DataOutputItems::SESSION));
        $dataoutputitems = \array_intersect($dataoutputitems ?? array(), $alldataoutputitems);
        if (!$dataoutputitems) {
            $dataoutputitems = \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('ApplicationState:default-dataoutputitems', array(\PoP\ComponentModel\Constants\DataOutputItems::META, \PoP\ComponentModel\Constants\DataOutputItems::DATASET_MODULE_SETTINGS, \PoP\ComponentModel\Constants\DataOutputItems::MODULE_DATA, \PoP\ComponentModel\Constants\DataOutputItems::DATABASES, \PoP\ComponentModel\Constants\DataOutputItems::SESSION));
        }
        // If not target, or invalid, reset it to "main"
        // We allow an empty target if none provided, so that we can generate the settings cache when no target is provided
        // (ie initial load) and when target is provided (ie loading pageSection)
        $targets = (array) \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('ApplicationState:targets', array(\PoP\ComponentModel\Constants\Targets::MAIN));
        if (!\in_array($target, $targets)) {
            $target = \PoP\ComponentModel\Constants\Targets::MAIN;
        }
        $platformmanager = \PoP\ComponentModel\StratumManagerFactory::getInstance();
        $stratum = $platformmanager->getStratum();
        $strata = $platformmanager->getStrata($stratum);
        $stratum_isdefault = $platformmanager->isDefaultStratum();
        $modulefilter_manager = \PoP\ComponentModel\Facades\ModuleFiltering\ModuleFilterManagerFacade::getInstance();
        $modulefilter = $modulefilter_manager->getSelectedModuleFilterName();
        // If there is not format, then set it to 'default'
        // This is needed so that the /generate/ generated configurations under a $model_instance_id (based on the value of $vars)
        // can match the same $model_instance_id when visiting that page
        $format = isset($_REQUEST[\PoP\ComponentModel\Constants\Params::FORMAT]) ? \strtolower($_REQUEST[\PoP\ComponentModel\Constants\Params::FORMAT]) : \PoP\ComponentModel\Constants\Values::DEFAULT;
        // By default, get the variables from the request
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        $variables = $fieldQueryInterpreter->getVariablesFromRequest();
        self::$vars = array(
            'nature' => $nature,
            'route' => $route,
            'output' => $output,
            'modulefilter' => $modulefilter,
            'actionpath' => $_REQUEST[\PoP\ComponentModel\Constants\Params::ACTION_PATH] ?? '',
            'target' => $target,
            'dataoutputitems' => $dataoutputitems,
            'datasources' => $datasources,
            'datastructure' => $datastructure,
            'dataoutputmode' => $dataoutputmode,
            'dboutputmode' => $dboutputmode,
            'mangled' => $mangled,
            'format' => $format,
            'actions' => $actions,
            'scheme' => $scheme,
            'stratum' => $stratum,
            'strata' => $strata,
            'stratum-isdefault' => $stratum_isdefault,
            'version' => $version,
            'variables' => $variables,
            'only-fieldname-as-outputkey' => \false,
            'namespace-types-and-interfaces' => \PoP\ComponentModel\ComponentConfiguration::namespaceTypesAndInterfaces(),
            'version-constraint' => \PoP\ComponentModel\Configuration\Request::getVersionConstraint(),
            'field-version-constraints' => \PoP\ComponentModel\Configuration\Request::getVersionConstraintsForFields(),
            'directive-version-constraints' => \PoP\ComponentModel\Configuration\Request::getVersionConstraintsForDirectives(),
            // By default, mutations are always enabled. Can be changed for the API
            'are-mutations-enabled' => \true,
        );
        if (\PoP\ComponentModel\ComponentConfiguration::enableConfigByParams()) {
            self::$vars['config'] = $_REQUEST[\PoP\ComponentModel\Constants\Params::CONFIG] ?? null;
        }
        // Set the routing state (eg: PoP Queried Object can add its information)
        self::$vars['routing-state'] = [];
        // Allow for plug-ins to add their own vars
        \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->doAction('ApplicationState:addVars', array(&self::$vars));
        self::augmentVarsProperties();
        return self::$vars;
    }
    public static function augmentVarsProperties()
    {
        $nature = self::$vars['nature'];
        self::$vars['routing-state']['is-standard'] = $nature == \PoP\Routing\RouteNatures::STANDARD;
        self::$vars['routing-state']['is-home'] = $nature == \PoP\Routing\RouteNatures::HOME;
        self::$vars['routing-state']['is-404'] = $nature == \PoP\Routing\RouteNatures::NOTFOUND;
        \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->doAction('augmentVarsProperties', array(&self::$vars));
    }
}

<?php

declare(strict_types=1);

namespace PoP\API\Hooks;

use PoP\API\ComponentConfiguration;
use PoP\API\Schema\QueryInputs;
use PoP\API\Configuration\Request;
use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\StratumManagerFactory;
use PoP\ComponentModel\State\ApplicationState;
use PoP\API\PersistedQueries\PersistedQueryUtils;
use PoP\API\State\ApplicationStateUtils;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\API\Response\Schemes as APISchemes;

class VarsHooks extends AbstractHookSet
{
    protected function init()
    {
        // Execute early, since others (eg: SPA) will be based on these updated values
        $this->hooksAPI->addAction('ApplicationState:addVars', array($this, 'addVars'), 5, 1);
        // Add functions as hooks, so we allow PoP_Application to set the 'routing-state' first
        $this->hooksAPI->addAction('ApplicationState:addVars', array($this, 'addURLParamVars'), 10, 1);
        $this->hooksAPI->addFilter(ModelInstance::HOOK_COMPONENTS_RESULT, array($this, 'getModelInstanceComponentsFromVars'));
    }

    /**
     * Override values for the API mode!
     * Whenever doing ?scheme=api, the specific configuration below must be set in the vars
     * @param array<array> $vars_in_array
     */
    public function addVars(array $vars_in_array): void
    {
        $vars =& $vars_in_array[0];
        if (isset($vars['scheme']) && $vars['scheme'] == APISchemes::API) {
            // For the API, the response is always JSON
            $vars['output'] = \GD_URLPARAM_OUTPUT_JSON;

            // Fetch datasetmodulesettings: needed to obtain the dbKeyPath to know where to find the database entries
            $vars['dataoutputitems'] = [
                \GD_URLPARAM_DATAOUTPUTITEMS_DATASETMODULESETTINGS,
                \GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA,
                \GD_URLPARAM_DATAOUTPUTITEMS_DATABASES,
            ];

            // dataoutputmode => Combined: there is no need to split the sources, then already combined them
            $vars['dataoutputmode'] = \GD_URLPARAM_DATAOUTPUTMODE_COMBINED;

            // dboutputmode => Combined: needed since we don't know under what database does the dbKeyPath point to. Then simply integrate all of them
            // Also, needed for REST/GraphQL APIs since all their data comes bundled all together
            $vars['dboutputmode'] = \GD_URLPARAM_DATABASESOUTPUTMODE_COMBINED;

            // Only the data stratum is needed
            $platformmanager = StratumManagerFactory::getInstance();
            $vars['stratum'] = \POP_STRATUM_DATA;
            $vars['strata'] = $platformmanager->getStrata($vars['stratum']);
            $vars['stratum-isdefault'] = $vars['stratum'] == $platformmanager->getDefaultStratum();

            // Do not print the entry module
            $vars['actions'][] = \POP_ACTION_REMOVE_ENTRYMODULE_FROM_OUTPUT;

            // Enable mutations?
            $vars['are-mutations-enabled'] = ComponentConfiguration::enableMutations();
        }
    }

    public function addURLParamVars(array $vars_in_array)
    {
        $vars =& $vars_in_array[0];
        if (isset($vars['scheme']) && $vars['scheme'] == APISchemes::API) {
            $this->addFieldsToVars($vars);
        }
    }

    private function addFieldsToVars(array &$vars)
    {
        if (isset($_REQUEST[QueryInputs::QUERY])) {
            $query = $_REQUEST[QueryInputs::QUERY];

            // If the query starts with "!", then it is the query name to a persisted query
            $query = PersistedQueryUtils::maybeGetPersistedQuery($query);

            // Set the query in $vars
            ApplicationStateUtils::maybeConvertQueryAndAddToVars($vars, $query);
        }
    }

    public function getModelInstanceComponentsFromVars($components)
    {
        // Allow WP API to set the "routing-state" first
        // Each page is an independent configuration
        $vars = ApplicationState::getVars();
        if (isset($vars['scheme']) && $vars['scheme'] == APISchemes::API) {
            $this->addFieldsToComponents($components);
        }

        // Namespaces change the configuration
        $components[] = TranslationAPIFacade::getInstance()->__('namespaced:', 'pop-engine') . ($vars['namespace-types-and-interfaces'] ?? false);

        return $components;
    }

    private function addFieldsToComponents(&$components)
    {
        $vars = ApplicationState::getVars();
        if ($fields = $vars['query'] ?? null) {
            // Serialize instead of implode, because $fields can contain $key => $value
            $components[] = TranslationAPIFacade::getInstance()->__('fields:', 'pop-engine') . serialize($fields);
        }
    }
}
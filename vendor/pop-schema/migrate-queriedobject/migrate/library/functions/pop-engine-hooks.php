<?php

namespace PoPSchema\QueriedObject;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Engine\FieldResolvers\OperatorGlobalFieldResolver;
class PoP_QueriedObject_VarsHooks
{
    public function __construct()
    {
        \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('ApplicationState:addVars', [$this, 'setQueriedObject'], 0, 1);
        \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction(\PoP\Engine\FieldResolvers\OperatorGlobalFieldResolver::HOOK_SAFEVARS, [$this, 'setSafeVars'], 10, 1);
    }
    /**
     * @param array<array> $vars_in_array
     */
    public function setQueriedObject(array $vars_in_array) : void
    {
        $vars =& $vars_in_array[0];
        $cmsqueriedobjectrouting = \PoPSchema\QueriedObject\CMSRoutingStateFactory::getInstance();
        // Allow to override the queried object, eg: by the AppShell
        list($queried_object, $queried_object_id) = \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('ApplicationState:queried-object', [$cmsqueriedobjectrouting->getQueriedObject(), $cmsqueriedobjectrouting->getQueriedObjectId()]);
        $vars['routing-state']['queried-object'] = $queried_object;
        $vars['routing-state']['queried-object-id'] = $queried_object_id;
    }
    /**
     * @param array<array> $vars_in_array
     */
    public function setSafeVars(array $vars_in_array) : void
    {
        // Remove the queried object
        $safeVars =& $vars_in_array[0];
        unset($safeVars['routing-state']['queried-object']);
    }
}
/**
 * Initialization
 */
new \PoPSchema\QueriedObject\PoP_QueriedObject_VarsHooks();

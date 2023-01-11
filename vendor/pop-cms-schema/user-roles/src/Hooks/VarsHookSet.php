<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserRoles\Hooks;

use PoP\Root\App;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\UserRoles\Constants\ModelInstanceComponentTypes;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoPCMSSchema\Users\Routing\RequestNature;
class VarsHookSet extends AbstractHookSet
{
    /**
     * @var \PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface|null
     */
    private $userRoleTypeAPI;
    /**
     * @param \PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface $userRoleTypeAPI
     */
    public final function setUserRoleTypeAPI($userRoleTypeAPI) : void
    {
        $this->userRoleTypeAPI = $userRoleTypeAPI;
    }
    protected final function getUserRoleTypeAPI() : UserRoleTypeAPIInterface
    {
        /** @var UserRoleTypeAPIInterface */
        return $this->userRoleTypeAPI = $this->userRoleTypeAPI ?? $this->instanceManager->getInstance(UserRoleTypeAPIInterface::class);
    }
    protected function init() : void
    {
        App::addFilter(ModelInstance::HOOK_ELEMENTS_RESULT, \Closure::fromCallable([$this, 'getModelInstanceElementsFromAppState']));
    }
    /**
     * @return string[]
     * @param string[] $elements
     */
    public function getModelInstanceElementsFromAppState($elements) : array
    {
        switch (App::getState('nature')) {
            case RequestNature::USER:
                $user_id = App::getState(['routing', 'queried-object-id']);
                // Author: it may depend on its role
                $component_types = App::applyFilters('\\PoP\\ComponentModel\\ModelInstanceProcessor_Utils:components_from_vars:type:userrole', array(ModelInstanceComponentTypes::USER_ROLE));
                if (\in_array(ModelInstanceComponentTypes::USER_ROLE, $component_types)) {
                    /** @var string */
                    $userRole = $this->getUserRoleTypeAPI()->getTheUserRole($user_id);
                    $elements[] = $this->__('user role:', 'pop-engine') . $userRole;
                }
                break;
        }
        return $elements;
    }
}

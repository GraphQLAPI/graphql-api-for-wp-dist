<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserRoles\TypeAPIs;

use PoP\Root\App;
use PoP\Root\Services\BasicServiceTrait;
abstract class AbstractUserRoleTypeAPI implements \PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface
{
    use BasicServiceTrait;
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getTheUserRole($userObjectOrID) : ?string
    {
        $roles = $this->getUserRoles($userObjectOrID);
        $role = $roles[0] ?? null;
        // Allow URE to override this function
        return App::applyFilters('getTheUserRole', $role, $userObjectOrID);
    }
    /**
     * @param string|int|object $userObjectOrID
     * @param string $capability
     */
    public function userCan($userObjectOrID, $capability) : bool
    {
        $capabilities = $this->getUserCapabilities($userObjectOrID);
        return \in_array($capability, $capabilities);
    }
    /**
     * @param string|int|object $userObjectOrID
     * @param string $role
     */
    public function hasRole($userObjectOrID, $role) : bool
    {
        $roles = $this->getUserRoles($userObjectOrID);
        return \in_array($role, $roles);
    }
}

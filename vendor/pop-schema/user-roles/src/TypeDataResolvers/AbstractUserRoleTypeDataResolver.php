<?php

declare (strict_types=1);
namespace PoPSchema\UserRoles\TypeDataResolvers;

use PoP\Hooks\HooksAPIInterface;
abstract class AbstractUserRoleTypeDataResolver implements \PoPSchema\UserRoles\TypeDataResolvers\UserRoleTypeDataResolverInterface
{
    /**
     * @var \PoP\Hooks\HooksAPIInterface
     */
    protected $hooksAPI;
    public function __construct(HooksAPIInterface $hooksAPI)
    {
        $this->hooksAPI = $hooksAPI;
    }
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getTheUserRole($userObjectOrID) : ?string
    {
        $roles = $this->getUserRoles($userObjectOrID);
        $role = $roles[0] ?? null;
        // Allow URE to override this function
        return $this->hooksAPI->applyFilters('getTheUserRole', $role, $userObjectOrID);
    }
    /**
     * @param string|int|object $userObjectOrID
     */
    public function userCan($userObjectOrID, string $capability) : bool
    {
        $capabilities = $this->getUserCapabilities($userObjectOrID);
        return \in_array($capability, $capabilities);
    }
    /**
     * @param string|int|object $userObjectOrID
     */
    public function hasRole($userObjectOrID, string $role) : bool
    {
        $roles = $this->getUserRoles($userObjectOrID);
        return \in_array($role, $roles);
    }
}

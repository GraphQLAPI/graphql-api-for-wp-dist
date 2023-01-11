<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserRoles\TypeAPIs;

interface UserRoleTypeAPIInterface
{
    /**
     * Admin role name
     */
    public function getAdminRoleName() : string;
    /**
     * @return string[]
     */
    public function getRoleNames() : array;
    /**
     * All available capabilities
     *
     * @return string[]
     */
    public function getCapabilities() : array;
    /**
     * @return string[]
     * @param string|int|object $userObjectOrID
     */
    public function getUserRoles($userObjectOrID) : array;
    /**
     * @return string[]
     * @param string|int|object $userObjectOrID
     */
    public function getUserCapabilities($userObjectOrID) : array;
    /**
     * @return string|null `null` if the user is not found, its first role otherwise
     * @param string|int|object $userObjectOrID
     */
    public function getTheUserRole($userObjectOrID) : ?string;
    /**
     * @param string|int|object $userObjectOrID
     * @param string $capability
     */
    public function userCan($userObjectOrID, $capability) : bool;
    /**
     * @param string|int|object $userObjectOrID
     * @param string $role
     */
    public function hasRole($userObjectOrID, $role) : bool;
}

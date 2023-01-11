<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesWP\Overrides\FieldResolvers\ObjectType;

use PoPCMSSchema\UserRoles\FieldResolvers\ObjectType\UserObjectTypeFieldResolver as UpstreamUserObjectTypeFieldResolver;
use PoPCMSSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver;

class UserObjectTypeFieldResolver extends UpstreamUserObjectTypeFieldResolver
{
    use RolesObjectTypeFieldResolverTrait;

    /**
     * @var \PoPCMSSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver|null
     */
    private $userRoleObjectTypeResolver;

    /**
     * @param \PoPCMSSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver $userRoleObjectTypeResolver
     */
    final public function setUserRoleObjectTypeResolver($userRoleObjectTypeResolver): void
    {
        $this->userRoleObjectTypeResolver = $userRoleObjectTypeResolver;
    }
    final protected function getUserRoleObjectTypeResolver(): UserRoleObjectTypeResolver
    {
        /** @var UserRoleObjectTypeResolver */
        return $this->userRoleObjectTypeResolver = $this->userRoleObjectTypeResolver ?? $this->instanceManager->getInstance(UserRoleObjectTypeResolver::class);
    }
}

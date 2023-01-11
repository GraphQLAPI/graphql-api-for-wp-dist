<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Exception\UserAuthorizationException;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;

interface UserAuthorizationSchemeRegistryInterface
{
    /**
     * @param \GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface $userAuthorizationScheme
     */
    public function addUserAuthorizationScheme($userAuthorizationScheme): void;
    /**
     * @return UserAuthorizationSchemeInterface[]
     */
    public function getUserAuthorizationSchemes(): array;
    /**
     * @throws UserAuthorizationException When the scheme is not registered
     * @param string $name
     */
    public function getUserAuthorizationScheme($name): UserAuthorizationSchemeInterface;
    /**
     * @throws UserAuthorizationException When no default object has been set
     */
    public function getDefaultUserAuthorizationScheme(): UserAuthorizationSchemeInterface;
}

<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\State;

use GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\EndpointExecuters\AdminEndpointExecuter;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLEndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\State\AbstractGraphQLEndpointExecuterAppStateProvider;

class AdminGraphQLEndpointExecuterAppStateProvider extends AbstractGraphQLEndpointExecuterAppStateProvider
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\EndpointExecuters\AdminEndpointExecuter|null
     */
    private $adminEndpointExecuter;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface|null
     */
    private $userAuthorization;

    /**
     * @param \GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\EndpointExecuters\AdminEndpointExecuter $adminEndpointExecuter
     */
    final public function setAdminEndpointExecuter($adminEndpointExecuter): void
    {
        $this->adminEndpointExecuter = $adminEndpointExecuter;
    }
    final protected function getAdminEndpointExecuter(): AdminEndpointExecuter
    {
        /** @var AdminEndpointExecuter */
        return $this->adminEndpointExecuter = $this->adminEndpointExecuter ?? $this->instanceManager->getInstance(AdminEndpointExecuter::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface $userAuthorization
     */
    final public function setUserAuthorization($userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        /** @var UserAuthorizationInterface */
        return $this->userAuthorization = $this->userAuthorization ?? $this->instanceManager->getInstance(UserAuthorizationInterface::class);
    }

    protected function getGraphQLEndpointExecuter(): GraphQLEndpointExecuterInterface
    {
        return $this->getAdminEndpointExecuter();
    }

    /**
     * @param array<string,mixed> $state
     */
    public function initialize(&$state): void
    {
        if (!$this->getUserAuthorization()->canAccessSchemaEditor()) {
            return;
        }
        parent::initialize($state);
    }

    /**
     * @param array<string,mixed> $state
     */
    public function consolidate(&$state): void
    {
        if (!$this->getUserAuthorization()->canAccessSchemaEditor()) {
            return;
        }
        parent::consolidate($state);
    }
}

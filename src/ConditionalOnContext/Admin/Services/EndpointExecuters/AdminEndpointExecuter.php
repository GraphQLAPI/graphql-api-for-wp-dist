<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\EndpointExecuters\AdminEndpointExecuterServiceTagInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\AbstractEndpointExecuter;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLEndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLByPoP\GraphQLRequest\Execution\QueryRetrieverInterface;
use PoP\EngineWP\HelperServices\TemplateHelpersInterface;
use WP_Post;

class AdminEndpointExecuter extends AbstractEndpointExecuter implements AdminEndpointExecuterServiceTagInterface, GraphQLEndpointExecuterInterface
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface|null
     */
    private $userAuthorization;
    /**
     * @var \GraphQLByPoP\GraphQLRequest\Execution\QueryRetrieverInterface|null
     */
    private $queryRetriever;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers|null
     */
    private $endpointHelpers;
    /**
     * @var \PoP\EngineWP\HelperServices\TemplateHelpersInterface|null
     */
    private $templateHelpers;

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
    /**
     * @param \GraphQLByPoP\GraphQLRequest\Execution\QueryRetrieverInterface $queryRetriever
     */
    final public function setQueryRetriever($queryRetriever): void
    {
        $this->queryRetriever = $queryRetriever;
    }
    final protected function getQueryRetriever(): QueryRetrieverInterface
    {
        /** @var QueryRetrieverInterface */
        return $this->queryRetriever = $this->queryRetriever ?? $this->instanceManager->getInstance(QueryRetrieverInterface::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers $endpointHelpers
     */
    final public function setEndpointHelpers($endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        /** @var EndpointHelpers */
        return $this->endpointHelpers = $this->endpointHelpers ?? $this->instanceManager->getInstance(EndpointHelpers::class);
    }
    /**
     * @param \PoP\EngineWP\HelperServices\TemplateHelpersInterface $templateHelpers
     */
    final public function setTemplateHelpers($templateHelpers): void
    {
        $this->templateHelpers = $templateHelpers;
    }
    final protected function getTemplateHelpers(): TemplateHelpersInterface
    {
        /** @var TemplateHelpersInterface */
        return $this->templateHelpers = $this->templateHelpers ?? $this->instanceManager->getInstance(TemplateHelpersInterface::class);
    }

    /**
     * Provide the query to execute and its variables
     *
     * @return array{0:?string,1:?array<string,mixed>} Array of 2 elements: [query, variables]
     * @param \WP_Post|null $graphQLQueryPost
     */
    public function getGraphQLQueryAndVariables($graphQLQueryPost): array
    {
        /**
         * Extract the query from the BODY through standard GraphQL endpoint execution
         */
        $graphQLQueryPayload = $this->getQueryRetriever()->extractRequestedGraphQLQueryPayload();
        return [
            $graphQLQueryPayload->query,
            $graphQLQueryPayload->variables,
        ];
    }

    /**
     * @param \WP_Post|null $customPost
     */
    public function doURLParamsOverrideGraphQLVariables($customPost): bool
    {
        return false;
    }

    /**
     * Execute the GraphQL query when posting to:
     * /wp-admin/edit.php?page=graphql_api&action=execute_query
     */
    public function isEndpointBeingRequested(): bool
    {
        if (!$this->getUserAuthorization()->canAccessSchemaEditor()) {
            return false;
        }
        return $this->getEndpointHelpers()->isRequestingAdminConfigurableSchemaGraphQLEndpoint();
    }

    public function executeEndpoint(): void
    {
        \add_action(
            'admin_init',
            \Closure::fromCallable([$this, 'includeJSONOutputTemplateAndExit'])
        );
    }

    /**
     * To print the JSON output, we use WordPress templates,
     * which are used only in the front-end.
     * When in the admin, we must manually load the template,
     * and then exit
     */
    public function includeJSONOutputTemplateAndExit(): void
    {
        // Make sure the user has access to the editor
        if ($this->getUserAuthorization()->canAccessSchemaEditor()) {
            include $this->getTemplateHelpers()->getGenerateDataAndPrepareAndSendResponseTemplateFile();
            die;
        }
    }
}

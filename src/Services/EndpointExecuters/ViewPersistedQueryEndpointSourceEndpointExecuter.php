<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockAccessors\PersistedQueryEndpointAPIHierarchyBlockAccessor;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointGraphiQLBlock;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\Helpers\GraphQLQueryPostTypeHelpers;
use WP_Post;

class ViewPersistedQueryEndpointSourceEndpointExecuter extends AbstractViewSourceEndpointExecuter
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType|null
     */
    private $graphQLPersistedQueryEndpointCustomPostType;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface|null
     */
    private $userAuthorization;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Helpers\GraphQLQueryPostTypeHelpers|null
     */
    private $graphQLQueryPostTypeHelpers;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\BlockAccessors\PersistedQueryEndpointAPIHierarchyBlockAccessor|null
     */
    private $persistedQueryEndpointAPIHierarchyBlockAccessor;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointGraphiQLBlock|null
     */
    private $persistedQueryEndpointGraphiQLBlock;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType
     */
    final public function setGraphQLPersistedQueryEndpointCustomPostType($graphQLPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPersistedQueryEndpointCustomPostType(): GraphQLPersistedQueryEndpointCustomPostType
    {
        /** @var GraphQLPersistedQueryEndpointCustomPostType */
        return $this->graphQLPersistedQueryEndpointCustomPostType = $this->graphQLPersistedQueryEndpointCustomPostType ?? $this->instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
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
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Helpers\GraphQLQueryPostTypeHelpers $graphQLQueryPostTypeHelpers
     */
    final public function setGraphQLQueryPostTypeHelpers($graphQLQueryPostTypeHelpers): void
    {
        $this->graphQLQueryPostTypeHelpers = $graphQLQueryPostTypeHelpers;
    }
    final protected function getGraphQLQueryPostTypeHelpers(): GraphQLQueryPostTypeHelpers
    {
        /** @var GraphQLQueryPostTypeHelpers */
        return $this->graphQLQueryPostTypeHelpers = $this->graphQLQueryPostTypeHelpers ?? $this->instanceManager->getInstance(GraphQLQueryPostTypeHelpers::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\BlockAccessors\PersistedQueryEndpointAPIHierarchyBlockAccessor $persistedQueryEndpointAPIHierarchyBlockAccessor
     */
    final public function setPersistedQueryEndpointAPIHierarchyBlockAccessor($persistedQueryEndpointAPIHierarchyBlockAccessor): void
    {
        $this->persistedQueryEndpointAPIHierarchyBlockAccessor = $persistedQueryEndpointAPIHierarchyBlockAccessor;
    }
    final protected function getPersistedQueryEndpointAPIHierarchyBlockAccessor(): PersistedQueryEndpointAPIHierarchyBlockAccessor
    {
        /** @var PersistedQueryEndpointAPIHierarchyBlockAccessor */
        return $this->persistedQueryEndpointAPIHierarchyBlockAccessor = $this->persistedQueryEndpointAPIHierarchyBlockAccessor ?? $this->instanceManager->getInstance(PersistedQueryEndpointAPIHierarchyBlockAccessor::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointGraphiQLBlock $persistedQueryEndpointGraphiQLBlock
     */
    final public function setPersistedQueryEndpointGraphiQLBlock($persistedQueryEndpointGraphiQLBlock): void
    {
        $this->persistedQueryEndpointGraphiQLBlock = $persistedQueryEndpointGraphiQLBlock;
    }
    final protected function getPersistedQueryEndpointGraphiQLBlock(): PersistedQueryEndpointGraphiQLBlock
    {
        /** @var PersistedQueryEndpointGraphiQLBlock */
        return $this->persistedQueryEndpointGraphiQLBlock = $this->persistedQueryEndpointGraphiQLBlock ?? $this->instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::PERSISTED_QUERIES;
    }

    protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface
    {
        return $this->getGraphQLPersistedQueryEndpointCustomPostType();
    }

    /**
     * Add the parent query to the rendering of the GraphQL Query CPT
     * @param string $content
     * @param \WP_Post $graphQLQueryPost
     */
    protected function getGraphQLQuerySourceContent($content, $graphQLQueryPost): string
    {
        $content = parent::getGraphQLQuerySourceContent($content, $graphQLQueryPost);

        /**
         * If the GraphQL query has a parent, possibly it is missing the query/variables/acl/ccl attributes,
         * which inherits from some parent
         * In that case, render the block twice:
         * 1. The current block, with missing attributes
         * 2. The final block, completing the missing attributes from its parent
         */
        if ($graphQLQueryPost->post_parent) {
            // Check if the user is authorized to see the content
            $ancestorContent = null;
            if ($this->getUserAuthorization()->canAccessSchemaEditor()) {
                /**
                 * If the query has a parent, also render the inherited output
                 */
                $persistedQueryEndpointAPIHierarchyBlockAttributes = $this->getPersistedQueryEndpointAPIHierarchyBlockAccessor()->getAttributes($graphQLQueryPost);
                if (
                    $persistedQueryEndpointAPIHierarchyBlockAttributes !== null
                    && $persistedQueryEndpointAPIHierarchyBlockAttributes->isInheritQuery()
                ) {
                    // Fetch the attributes using inheritance
                    list(
                        $inheritedGraphQLQuery,
                        $inheritedGraphQLVariables
                    ) = $this->getGraphQLQueryPostTypeHelpers()->getGraphQLQueryPostAttributes($graphQLQueryPost, true);
                    // To render the variables in the block, they must be json_encoded
                    if ($inheritedGraphQLVariables) {
                        $inheritedGraphQLVariables = (string)json_encode($inheritedGraphQLVariables);
                    }
                    // Render the block again, using the inherited attributes
                    $inheritedGraphQLBlockAttributes = [
                        PersistedQueryEndpointGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $inheritedGraphQLQuery,
                        PersistedQueryEndpointGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $inheritedGraphQLVariables,
                    ];
                    // Add the new rendering to the output, and a description for each
                    $ancestorContent = $this->getPersistedQueryEndpointGraphiQLBlock()->renderBlock($inheritedGraphQLBlockAttributes, '');
                }
            } else {
                $ancestorContent = $this->getPersistedQueryEndpointGraphiQLBlock()->renderUnauthorizedAccess();
            }
            if (!is_null($ancestorContent)) {
                $content = sprintf(
                    '%s%s<hr/>%s%s',
                    \__('<p><u>GraphQL query, inheriting properties from ancestor(s): </u></p>'),
                    $ancestorContent,
                    \__('<p><u>GraphQL query, as defined in this level: </u></p>'),
                    $content
                );
            }
        }

        return $content;
    }
}

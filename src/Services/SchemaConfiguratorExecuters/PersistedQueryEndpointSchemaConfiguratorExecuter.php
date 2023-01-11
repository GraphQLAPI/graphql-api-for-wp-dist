<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQueryEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;

class PersistedQueryEndpointSchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQueryEndpointSchemaConfigurator|null
     */
    private $persistedQueryEndpointSchemaConfigurator;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType|null
     */
    private $graphQLPersistedQueryEndpointCustomPostType;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator
     */
    final public function setPersistedQueryEndpointSchemaConfigurator($persistedQueryEndpointSchemaConfigurator): void
    {
        $this->persistedQueryEndpointSchemaConfigurator = $persistedQueryEndpointSchemaConfigurator;
    }
    final protected function getPersistedQueryEndpointSchemaConfigurator(): PersistedQueryEndpointSchemaConfigurator
    {
        /** @var PersistedQueryEndpointSchemaConfigurator */
        return $this->persistedQueryEndpointSchemaConfigurator = $this->persistedQueryEndpointSchemaConfigurator ?? $this->instanceManager->getInstance(PersistedQueryEndpointSchemaConfigurator::class);
    }
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

    protected function getCustomPostType(): string
    {
        return $this->getGraphQLPersistedQueryEndpointCustomPostType()->getCustomPostType();
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getPersistedQueryEndpointSchemaConfigurator();
    }
}

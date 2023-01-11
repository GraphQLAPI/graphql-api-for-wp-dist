<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\CustomEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;

class CustomEndpointSchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\CustomEndpointSchemaConfigurator|null
     */
    private $customEndpointSchemaConfigurator;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType|null
     */
    private $graphQLCustomEndpointCustomPostType;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\CustomEndpointSchemaConfigurator $customEndpointSchemaConfigurator
     */
    final public function setCustomEndpointSchemaConfigurator($customEndpointSchemaConfigurator): void
    {
        $this->customEndpointSchemaConfigurator = $customEndpointSchemaConfigurator;
    }
    final protected function getCustomEndpointSchemaConfigurator(): CustomEndpointSchemaConfigurator
    {
        /** @var CustomEndpointSchemaConfigurator */
        return $this->customEndpointSchemaConfigurator = $this->customEndpointSchemaConfigurator ?? $this->instanceManager->getInstance(CustomEndpointSchemaConfigurator::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType
     */
    final public function setGraphQLCustomEndpointCustomPostType($graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    final protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        /** @var GraphQLCustomEndpointCustomPostType */
        return $this->graphQLCustomEndpointCustomPostType = $this->graphQLCustomEndpointCustomPostType ?? $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
    }

    protected function getCustomPostType(): string
    {
        return $this->getGraphQLCustomEndpointCustomPostType()->getCustomPostType();
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->getCustomEndpointSchemaConfigurator();
    }
}

<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQueryEndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class PersistedQueryEndpointSchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\PersistedQueryEndpointSchemaConfigurator
     */
    protected $persistedQueryEndpointSchemaConfigurator;
    public function __construct(
        InstanceManagerInterface $instanceManager,
        PersistedQueryEndpointSchemaConfigurator $persistedQueryEndpointSchemaConfigurator
    ) {
        $this->persistedQueryEndpointSchemaConfigurator = $persistedQueryEndpointSchemaConfigurator;
        parent::__construct($instanceManager);
    }

    protected function getCustomPostType(): string
    {
        /** @var GraphQLPersistedQueryEndpointCustomPostType */
        $customPostTypeService = $this->instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
        return $customPostTypeService->getCustomPostType();
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->persistedQueryEndpointSchemaConfigurator;
    }
}

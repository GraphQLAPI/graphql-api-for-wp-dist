<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\General\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\General\RequestParams;
use GraphQLAPI\GraphQLAPI\SchemaConfigurators\SchemaConfiguratorInterface;
use GraphQLAPI\GraphQLAPI\SchemaConfigurators\PersistedQuerySchemaConfigurator;

class EditingPersistedQuerySchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    /**
     * Initialize the configuration if editing a persisted query
     */
    protected function getCustomPostID(): ?int
    {
        if (EndpointHelpers::isRequestingAdminGraphQLEndpoint() && isset($_REQUEST[RequestParams::PERSISTED_QUERY_ID])) {
            return (int) $_REQUEST[RequestParams::PERSISTED_QUERY_ID];
        }
        return null;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return new PersistedQuerySchemaConfigurator();
    }
}

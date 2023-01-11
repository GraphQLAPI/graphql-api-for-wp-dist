<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\SchemaConfigurationExecuterInterface;

interface SchemaConfigurationExecuterRegistryInterface
{
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\SchemaConfigurationExecuterInterface $schemaConfigurationExecuter
     */
    public function addSchemaConfigurationExecuter($schemaConfigurationExecuter): void;
    /**
     * @return SchemaConfigurationExecuterInterface[]
     */
    public function getSchemaConfigurationExecuters(): array;
    /**
     * @return SchemaConfigurationExecuterInterface[]
     */
    public function getEnabledSchemaConfigurationExecuters(): array;
}

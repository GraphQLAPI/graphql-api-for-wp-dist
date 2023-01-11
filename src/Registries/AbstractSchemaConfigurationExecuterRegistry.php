<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\SchemaConfigurationExecuterInterface;
use PoP\Root\Services\ServiceInterface;

abstract class AbstractSchemaConfigurationExecuterRegistry implements SchemaConfigurationExecuterRegistryInterface
{
    /**
     * @var SchemaConfigurationExecuterInterface[]
     */
    protected $schemaConfigurationExecuters = [];

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\SchemaConfigurationExecuterInterface $schemaConfigurationExecuter
     */
    public function addSchemaConfigurationExecuter($schemaConfigurationExecuter): void
    {
        $this->schemaConfigurationExecuters[] = $schemaConfigurationExecuter;
    }
    /**
     * @return SchemaConfigurationExecuterInterface[]
     */
    public function getSchemaConfigurationExecuters(): array
    {
        return $this->schemaConfigurationExecuters;
    }
    /**
     * @return SchemaConfigurationExecuterInterface[]
     */
    public function getEnabledSchemaConfigurationExecuters(): array
    {
        return array_values(array_filter(
            $this->getSchemaConfigurationExecuters(),
            function (ServiceInterface $service) {
                return $service->isServiceEnabled();
            }
        ));
    }
}

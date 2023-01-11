<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractSchemaConfigurationExecuter implements SchemaConfigurationExecuterInterface
{
    use BasicServiceTrait;

    /**
     * @var \GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface|null
     */
    private $moduleRegistry;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers|null
     */
    private $blockHelpers;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface $moduleRegistry
     */
    final public function setModuleRegistry($moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry = $this->moduleRegistry ?? $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers $blockHelpers
     */
    final public function setBlockHelpers($blockHelpers): void
    {
        $this->blockHelpers = $blockHelpers;
    }
    final protected function getBlockHelpers(): BlockHelpers
    {
        /** @var BlockHelpers */
        return $this->blockHelpers = $this->blockHelpers ?? $this->instanceManager->getInstance(BlockHelpers::class);
    }

    /**
     * @return array<string,mixed>|null Data inside the block is saved as key (string) => value
     * @param int $schemaConfigurationID
     */
    protected function getSchemaConfigBlockDataItem($schemaConfigurationID): ?array
    {
        $block = $this->getBlock();
        return $this->getBlockHelpers()->getSingleBlockOfTypeFromCustomPost(
            $schemaConfigurationID,
            $block
        );
    }

    abstract protected function getBlock(): BlockInterface;

    public function getEnablingModule(): ?string
    {
        return null;
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        $enablingModule = $this->getEnablingModule();
        if ($enablingModule !== null) {
            return $this->getModuleRegistry()->isModuleEnabled($enablingModule);
        }
        return true;
    }
}

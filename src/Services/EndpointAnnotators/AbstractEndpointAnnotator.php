<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use PoP\Root\Services\BasicServiceTrait;
use WP_Post;

abstract class AbstractEndpointAnnotator implements EndpointAnnotatorInterface
{
    use BasicServiceTrait;

    /**
     * @var \GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface|null
     */
    private $moduleRegistry;

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
     * Add actions to the CPT list
     * @param array<string,string> $actions
     * @param \WP_Post $post
     */
    public function addCustomPostTypeTableActions(&$actions, $post): void
    {
        // Do nothing
    }

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
        if ($enablingModule !== null && !$this->getModuleRegistry()->isModuleEnabled($enablingModule)) {
            return false;
        }

        return true;
    }

    abstract protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface;
}

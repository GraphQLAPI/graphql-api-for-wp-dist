<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\AppObjects;

/**
 * Configuration to cache the container
 */
class ContainerCacheConfiguration
{
    /**
     * @readonly
     * @var bool
     */
    private $cacheContainerConfiguration;
    /**
     * @readonly
     * @var string|null
     */
    private $containerConfigurationCacheNamespace;
    /**
     * @readonly
     * @var string|null
     */
    private $containerConfigurationCacheDirectory;
    public function __construct(bool $cacheContainerConfiguration, ?string $containerConfigurationCacheNamespace, ?string $containerConfigurationCacheDirectory)
    {
        $this->cacheContainerConfiguration = $cacheContainerConfiguration;
        $this->containerConfigurationCacheNamespace = $containerConfigurationCacheNamespace;
        $this->containerConfigurationCacheDirectory = $containerConfigurationCacheDirectory;
    }
    public function cacheContainerConfiguration(): bool
    {
        return $this->cacheContainerConfiguration;
    }

    public function getContainerConfigurationCacheNamespace(): ?string
    {
        return $this->containerConfigurationCacheNamespace;
    }

    public function getContainerConfigurationCacheDirectory(): ?string
    {
        return $this->containerConfigurationCacheDirectory;
    }
}

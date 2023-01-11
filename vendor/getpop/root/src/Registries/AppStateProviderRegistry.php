<?php

declare (strict_types=1);
namespace PoP\Root\Registries;

use PoP\Root\State\AppStateProviderInterface;
class AppStateProviderRegistry implements \PoP\Root\Registries\AppStateProviderRegistryInterface
{
    /**
     * @var AppStateProviderInterface[]
     */
    protected $appStateProviders = [];
    /**
     * @param \PoP\Root\State\AppStateProviderInterface $appStateProvider
     */
    public function addAppStateProvider($appStateProvider) : void
    {
        $this->appStateProviders[] = $appStateProvider;
    }
    /**
     * @return AppStateProviderInterface[]
     */
    public function getAppStateProviders() : array
    {
        return $this->appStateProviders;
    }
    /**
     * @return AppStateProviderInterface[]
     */
    public function getEnabledAppStateProviders() : array
    {
        return \array_values(\array_filter($this->getAppStateProviders(), function (AppStateProviderInterface $service) {
            return $service->isServiceEnabled();
        }));
    }
}

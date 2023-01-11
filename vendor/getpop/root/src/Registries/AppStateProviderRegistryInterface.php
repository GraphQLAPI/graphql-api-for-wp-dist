<?php

declare (strict_types=1);
namespace PoP\Root\Registries;

use PoP\Root\State\AppStateProviderInterface;
interface AppStateProviderRegistryInterface
{
    /**
     * @param \PoP\Root\State\AppStateProviderInterface $appStateProvider
     */
    public function addAppStateProvider($appStateProvider) : void;
    /**
     * @return AppStateProviderInterface[]
     */
    public function getAppStateProviders() : array;
    /**
     * @return AppStateProviderInterface[]
     */
    public function getEnabledAppStateProviders() : array;
}

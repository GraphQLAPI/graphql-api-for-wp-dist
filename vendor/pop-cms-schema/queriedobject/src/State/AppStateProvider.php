<?php

declare (strict_types=1);
namespace PoPCMSSchema\QueriedObject\State;

use PoP\Root\App;
use PoPCMSSchema\QueriedObject\Routing\CMSRoutingStateServiceInterface;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\State\AbstractAppStateProvider;
class AppStateProvider extends AbstractAppStateProvider
{
    /**
     * @var \PoPCMSSchema\QueriedObject\Routing\CMSRoutingStateServiceInterface|null
     */
    private $cmsRoutingStateService;
    /**
     * @param \PoPCMSSchema\QueriedObject\Routing\CMSRoutingStateServiceInterface $cmsRoutingStateService
     */
    public final function setCMSRoutingStateService($cmsRoutingStateService) : void
    {
        $this->cmsRoutingStateService = $cmsRoutingStateService;
    }
    protected final function getCMSRoutingStateService() : CMSRoutingStateServiceInterface
    {
        /** @var CMSRoutingStateServiceInterface */
        return $this->cmsRoutingStateService = $this->cmsRoutingStateService ?? $this->instanceManager->getInstance(CMSRoutingStateServiceInterface::class);
    }
    /**
     * @param array<string,mixed> $state
     */
    public function initialize(&$state) : void
    {
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if ($rootModuleConfiguration->enablePassingStateViaRequest()) {
            $state['routing']['queried-object'] = $this->getCMSRoutingStateService()->getQueriedObject();
            $state['routing']['queried-object-id'] = $this->getCMSRoutingStateService()->getQueriedObjectID();
        } else {
            $state['routing']['queried-object'] = null;
            $state['routing']['queried-object-id'] = null;
        }
    }
}

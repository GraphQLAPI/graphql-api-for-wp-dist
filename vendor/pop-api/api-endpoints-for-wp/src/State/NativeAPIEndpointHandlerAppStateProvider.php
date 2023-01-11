<?php

declare(strict_types=1);

namespace PoPAPI\APIEndpointsForWP\State;

use PoPAPI\APIEndpoints\EndpointHandlerInterface;
use PoPAPI\APIEndpointsForWP\EndpointHandlers\NativeAPIEndpointHandler;
use PoPAPI\APIEndpointsForWP\State\AbstractAPIEndpointHandlerAppStateProvider;

class NativeAPIEndpointHandlerAppStateProvider extends AbstractAPIEndpointHandlerAppStateProvider
{
    /**
     * @var \PoPAPI\APIEndpointsForWP\EndpointHandlers\NativeAPIEndpointHandler|null
     */
    private $nativeAPIEndpointHandler;

    /**
     * @param \PoPAPI\APIEndpointsForWP\EndpointHandlers\NativeAPIEndpointHandler $nativeAPIEndpointHandler
     */
    final public function setNativeAPIEndpointHandler($nativeAPIEndpointHandler): void
    {
        $this->nativeAPIEndpointHandler = $nativeAPIEndpointHandler;
    }
    final protected function getNativeAPIEndpointHandler(): NativeAPIEndpointHandler
    {
        /** @var NativeAPIEndpointHandler */
        return $this->nativeAPIEndpointHandler = $this->nativeAPIEndpointHandler ?? $this->instanceManager->getInstance(NativeAPIEndpointHandler::class);
    }

    protected function getEndpointHandler(): EndpointHandlerInterface
    {
        return $this->getNativeAPIEndpointHandler();
    }
}

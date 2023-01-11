<?php

declare (strict_types=1);
namespace PoP\Root\Hooks;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\BasicServiceTrait;
abstract class AbstractHookSet extends AbstractAutomaticallyInstantiatedService
{
    use BasicServiceTrait;
    public final function initialize() : void
    {
        // Initialize the hooks
        $this->init();
    }
    /**
     * Initialize the hooks
     */
    protected abstract function init() : void;
}

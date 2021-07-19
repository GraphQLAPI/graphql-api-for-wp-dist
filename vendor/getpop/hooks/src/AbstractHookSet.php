<?php

declare (strict_types=1);
namespace PoP\Hooks;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
abstract class AbstractHookSet extends AbstractAutomaticallyInstantiatedService
{
    /**
     * @var \PoP\Hooks\HooksAPIInterface
     */
    protected $hooksAPI;
    /**
     * @var \PoP\Translation\TranslationAPIInterface
     */
    protected $translationAPI;
    /**
     * @var \PoP\ComponentModel\Instances\InstanceManagerInterface
     */
    protected $instanceManager;
    public function __construct(HooksAPIInterface $hooksAPI, TranslationAPIInterface $translationAPI, InstanceManagerInterface $instanceManager)
    {
        $this->hooksAPI = $hooksAPI;
        $this->translationAPI = $translationAPI;
        $this->instanceManager = $instanceManager;
    }
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

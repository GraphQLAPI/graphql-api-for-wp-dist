<?php

declare (strict_types=1);
namespace PoP\Hooks;

use PoP\Hooks\HooksAPIInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Translation\TranslationAPIInterface;
abstract class AbstractHookSet extends \PoP\Root\Services\AbstractAutomaticallyInstantiatedService
{
    /**
     * @var \PoP\Hooks\HooksAPIInterface
     */
    protected $hooksAPI;
    /**
     * @var \PoP\Translation\TranslationAPIInterface
     */
    protected $translationAPI;
    public function __construct(\PoP\Hooks\HooksAPIInterface $hooksAPI, \PoP\Translation\TranslationAPIInterface $translationAPI)
    {
        $this->hooksAPI = $hooksAPI;
        $this->translationAPI = $translationAPI;
    }
    public final function initialize() : void
    {
        // Initialize the hooks
        $this->init();
    }
    /**
     * Initialize the hooks
     *
     * @return void
     */
    protected abstract function init();
}

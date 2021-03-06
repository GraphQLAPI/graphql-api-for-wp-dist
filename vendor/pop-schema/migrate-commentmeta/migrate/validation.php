<?php

namespace PoPSchema\CommentMeta;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
\define('POP_COMMENTMETA_POP_ENGINE_MIN_VERSION', 0.1);
class Validation
{
    public function getProviderValidationClass()
    {
        return \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('PoP_CommentMeta_Validation:provider-validation-class', null);
    }
    public function validate()
    {
        $success = \true;
        $provider_validation_class = $this->getProviderValidationClass();
        if (\is_null($provider_validation_class)) {
            \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'providerinstall_warning'));
            \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'providerinstall_warning'));
            $success = \false;
        } elseif (!(new $provider_validation_class())->validate()) {
            \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'providerinitialize_warning'));
            \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'providerinitialize_warning'));
            $success = \false;
        }
        if (!\defined('POP_ENGINE_VERSION')) {
            \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'installWarning'));
            \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'installWarning'));
            $success = \false;
        } elseif (!\defined('POP_ENGINE_INITIALIZED')) {
            \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'initializeWarning'));
            \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'initializeWarning'));
            $success = \false;
        } elseif (POP_COMMENTMETA_POP_ENGINE_MIN_VERSION > POP_ENGINE_VERSION) {
            \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'versionWarning'));
            \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'versionWarning'));
        }
        return $success;
    }
    public function providerinstall_warning()
    {
        $this->providerinstall_warning_notice(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('PoP Comment Meta', 'pop-commentmeta'));
    }
    public function providerinitialize_warning()
    {
        $this->providerinitialize_warning_notice(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('PoP Comment Meta', 'pop-commentmeta'));
    }
    public function initializeWarning()
    {
        $this->dependencyInitializationWarning(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('PoP Comment Meta', 'pop-commentmeta'), \PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('PoP Engine', 'pop-commentmeta'));
    }
    public function installWarning()
    {
        $this->dependencyInstallationWarning(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('PoP Comment Meta', 'pop-commentmeta'), \PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('PoP Engine', 'pop-commentmeta'), 'https://github.com/leoloso/PoP');
    }
    public function versionWarning()
    {
        $this->dependencyVersionWarning(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('PoP Comment Meta', 'pop-commentmeta'), \PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('PoP Engine', 'pop-commentmeta'), 'https://github.com/leoloso/PoP', POP_COMMENTMETA_POP_ENGINE_MIN_VERSION);
    }
    protected function providerinstall_warning_notice($plugin)
    {
        $this->adminNotice(\sprintf(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('Error: %s', 'pop-engine-webplatform'), \sprintf(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('There is no provider (underlying implementation) for <strong>%s</strong>.', 'pop-engine-webplatform'), $plugin)));
    }
    protected function dependencyInstallationWarning($plugin, $dependency, $dependency_url)
    {
        $this->adminNotice(\sprintf(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('Error: %s', 'pop-engine-webplatform'), \sprintf(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('<strong>%s</strong> is not installed/activated. Without it, <strong>%s</strong> will not work. Please install this plugin from your plugin installer or download it <a href="%s" target="_blank">from here</a>.', 'pop-engine-webplatform'), $dependency, $plugin, $dependency_url)));
    }
    protected function dependencyInitializationWarning($plugin, $dependency)
    {
        $this->adminNotice(\sprintf(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('Error: %s', 'pop-engine-webplatform'), \sprintf(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('<strong>%s</strong> is not initialized properly. As a consequence, <strong>%s</strong> has not been loaded.', 'pop-engine-webplatform'), $dependency, $plugin)));
    }
    protected function dependencyVersionWarning($plugin, $dependency, $dependency_url, $dependency_min_version)
    {
        $this->adminNotice(\sprintf(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('Error: %s', 'pop-engine-webplatform'), \sprintf(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('<strong>%s</strong> requires version %s or bigger of <strong>%s</strong>. Please update this plugin from your plugin installer or download it <a href="%s" target="_blank">from here</a>.', 'pop-engine-webplatform'), $plugin, $dependency_min_version, $dependency, $dependency_url)));
    }
    protected function adminNotice($message)
    {
        ?>
        <div class="error">
            <p>
        <?php 
        echo $message;
        ?><br/>
                <em>
        <?php 
        _e('Only admins see this message.', 'pop-engine-webplatform');
        ?>
                </em>
            </p>
        </div>
        <?php 
    }
}

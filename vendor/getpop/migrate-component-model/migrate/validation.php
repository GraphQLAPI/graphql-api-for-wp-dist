<?php

namespace PoP\ComponentModel;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
class Validation
{
    public function getProviderValidationClass()
    {
        return \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('PoP_Engine_Validation:provider-validation-class', null);
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
        return $success;
    }
    public function providerinstall_warning()
    {
        $this->providerinstall_warning_notice(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('PoP Engine', 'pop-engine'));
    }
    protected function providerinstall_warning_notice($plugin)
    {
        $this->adminNotice(\sprintf(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('Error: %s', 'pop-engine-webplatform'), \sprintf(\PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('There is no provider (underlying implementation) for <strong>%s</strong>.', 'pop-engine-webplatform'), $plugin)));
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

<?php

declare (strict_types=1);
namespace PoP\Root\Services;

use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoP\Root\Translation\TranslationAPIInterface;
trait StandaloneServiceTrait
{
    protected function getTranslationAPI() : TranslationAPIInterface
    {
        return TranslationAPIFacade::getInstance();
    }
    /**
     * Shortcut function
     * @param string $text
     * @param string $domain
     */
    protected function __($text, $domain = 'default') : string
    {
        return $this->getTranslationAPI()->__($text, $domain);
    }
}

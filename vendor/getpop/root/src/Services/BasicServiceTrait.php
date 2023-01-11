<?php

declare (strict_types=1);
namespace PoP\Root\Services;

use PoP\Root\Services\WithInstanceManagerServiceTrait;
use PoP\Root\Translation\TranslationAPIInterface;
trait BasicServiceTrait
{
    use WithInstanceManagerServiceTrait;
    /**
     * @var \PoP\Root\Translation\TranslationAPIInterface|null
     */
    private $translationAPI;
    /**
     * @param \PoP\Root\Translation\TranslationAPIInterface $translationAPI
     */
    public final function setTranslationAPI($translationAPI) : void
    {
        $this->translationAPI = $translationAPI;
    }
    protected final function getTranslationAPI() : TranslationAPIInterface
    {
        /** @var TranslationAPIInterface */
        return $this->translationAPI = $this->translationAPI ?? $this->instanceManager->getInstance(TranslationAPIInterface::class);
    }
    /**
     * Shortcut function
     * @param string $text
     * @param string $domain
     */
    protected final function __($text, $domain = 'default') : string
    {
        return $this->getTranslationAPI()->__($text, $domain);
    }
}

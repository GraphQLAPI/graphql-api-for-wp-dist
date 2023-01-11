<?php

declare (strict_types=1);
namespace PoP\Root\Translation;

class BasicTranslationAPI implements \PoP\Root\Translation\TranslationAPIInterface
{
    /**
     * @param string $text
     * @param string $domain
     */
    public function __($text, $domain = 'default') : string
    {
        return $text;
    }
}

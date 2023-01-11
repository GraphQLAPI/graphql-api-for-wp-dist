<?php

declare (strict_types=1);
namespace PoP\Root\Translation;

interface TranslationAPIInterface
{
    /**
     * @param string $text
     * @param string $domain
     */
    public function __($text, $domain = 'default') : string;
}

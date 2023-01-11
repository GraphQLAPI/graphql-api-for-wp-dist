<?php

declare(strict_types=1);

namespace PoP\RootWP\Translation;

use PoP\Root\Translation\TranslationAPIInterface;

use function __;

class TranslationAPI implements TranslationAPIInterface
{
    /**
     * @param string $text
     * @param string $domain
     */
    public function __($text, $domain = 'default'): string
    {
        return __($text, $domain);
    }
}

<?php

declare (strict_types=1);
namespace PoP\Root\FeedbackItemProviders;

interface FeedbackItemProviderInterface
{
    /**
     * @return string[]
     */
    public function getCodes() : array;
    /**
     * @param string $code
     */
    public function getNamespacedCode($code) : string;
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string;
    /**
     * @param string|int|float|bool|null ...$args
     * @param string $code
     */
    public function getMessage($code, ...$args) : string;
    /**
     * @param string $code
     */
    public function getCategory($code) : string;
    /**
     * @param string $code
     */
    public function getSpecifiedByURL($code) : ?string;
}

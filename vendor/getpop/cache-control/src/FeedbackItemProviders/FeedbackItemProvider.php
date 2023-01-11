<?php

declare (strict_types=1);
namespace PoP\CacheControl\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;
class FeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = 'e1';
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::E1];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::E1:
                return $this->__('The value for \'maxAge\' must either be a positive number, or \'0\' to avoid caching', 'cache-control');
            default:
                return parent::getMessagePlaceholder($code);
        }
    }
    /**
     * @param string $code
     */
    public function getCategory($code) : string
    {
        switch ($code) {
            case self::E1:
                return FeedbackCategories::ERROR;
            default:
                return parent::getCategory($code);
        }
    }
}

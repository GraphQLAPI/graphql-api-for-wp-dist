<?php

declare (strict_types=1);
namespace PoP\Root\FeedbackItemProviders;

use PoP\Root\Feedback\FeedbackCategories;
class GenericFeedbackItemProvider extends \PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider
{
    public const E1 = 'e1';
    public const D1 = 'd1';
    public const L1 = 'l1';
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::E1, self::D1, self::L1];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::E1:
            case self::D1:
            case self::L1:
                return '%s';
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
            case self::D1:
                return FeedbackCategories::DEPRECATION;
            case self::L1:
                return FeedbackCategories::LOG;
            default:
                return parent::getCategory($code);
        }
    }
}

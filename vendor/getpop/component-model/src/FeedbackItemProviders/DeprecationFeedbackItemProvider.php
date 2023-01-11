<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;
class DeprecationFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const D1 = 'd1';
    public const D2 = 'd2';
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::D1, self::D2];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::D1:
                return $this->__('Field \'%s\' is deprecated: %s', 'component-model');
            case self::D2:
                return $this->__('Directive \'%s\' is deprecated: %s', 'component-model');
            default:
                return parent::getMessagePlaceholder($code);
        }
    }
    /**
     * @param string $code
     */
    public function getCategory($code) : string
    {
        return FeedbackCategories::DEPRECATION;
    }
}

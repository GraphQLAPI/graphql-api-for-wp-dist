<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;
class FieldResolutionErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E2 = '2';
    public const E3 = '3';
    public const E4 = '4';
    public const E5 = '5';
    public const E6 = '6';
    public const E7 = '7';
    public const E8 = '8';
    public const E9 = '9';
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::E2, self::E3, self::E4, self::E5, self::E6, self::E7, self::E8, self::E9];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::E2:
                return $this->__('Field \'%s\' could not be processed due to the error(s) from its arguments', 'component-model');
            case self::E3:
                return $this->__('Non-nullable field \'%s\' cannot return null', 'component-model');
            case self::E4:
                return $this->__('Field \'%s\' must not return an array, but returned \'%s\'', 'component-model');
            case self::E5:
                return $this->__('Field \'%s\' must return an array, but returned \'%s\'', 'component-model');
            case self::E6:
                return $this->__('Field \'%s\' must not return an array with null items', 'component-model');
            case self::E7:
                return $this->__('Array value in field \'%s\' must not contain arrays, but returned \'%s\'', 'component-model');
            case self::E8:
                return $this->__('Field \'%s\' must return an array of arrays, but returned \'%s\'', 'component-model');
            case self::E9:
                return $this->__('Field \'%s\' must not return an array of arrays with null items', 'component-model');
            default:
                return parent::getMessagePlaceholder($code);
        }
    }
    /**
     * @param string $code
     */
    public function getCategory($code) : string
    {
        return FeedbackCategories::ERROR;
    }
}

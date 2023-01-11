<?php

declare (strict_types=1);
namespace PoP\Engine\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;
class ErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = 'e1';
    public const E1A = 'e1a';
    public const E2 = 'e2';
    public const E4 = 'e4';
    public const E5 = 'e5';
    public const E6 = 'e6';
    public const E7 = 'e7';
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::E1, self::E1A, self::E2, self::E4, self::E5, self::E6, self::E7];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::E1:
                return $this->__('Operation \'%s\' is not available', 'engine');
            case self::E1A:
                return $this->__('The operation is not available', 'engine');
            case self::E2:
                return $this->__('Field \'%s\' hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'engine');
            case self::E4:
                return $this->__('The value to which the directive is applied is not an array or object', 'engine');
            case self::E5:
                return $this->__('No composed directives were provided to \'%s\'', 'engine');
            case self::E6:
                return $this->__('There is no property \'%s\' in the application state', 'engine');
            case self::E7:
                return $this->__('%s', 'engine');
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

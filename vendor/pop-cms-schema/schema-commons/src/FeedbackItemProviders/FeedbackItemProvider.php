<?php

declare (strict_types=1);
namespace PoPCMSSchema\SchemaCommons\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;
class FeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = 'e1';
    public const E2 = 'e2';
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::E1, self::E2];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::E1:
                return $this->__('The value for input field \'%s\' in input object \'%s\' cannot be below \'%s\'', 'schema-commons');
            case self::E2:
                return $this->__('The value for input field \'%s\' in input object \'%s\' cannot be above \'%s\', but \'%s\' was provided', 'schema-commons');
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
            case self::E2:
                return FeedbackCategories::ERROR;
            default:
                return parent::getCategory($code);
        }
    }
}

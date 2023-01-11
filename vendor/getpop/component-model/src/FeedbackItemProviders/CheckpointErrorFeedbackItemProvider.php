<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;
class CheckpointErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = '1';
    public const E2 = '2';
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
                return $this->__('Mutations cannot be executed', 'component-model');
            case self::E2:
                return $this->__('Use the operation \'mutation\' to mutate data', 'component-model');
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

<?php

declare (strict_types=1);
namespace PoP\Engine\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;
class CheckpointErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = '1';
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
                return $this->__('REQUEST_METHOD is not \'POST\'', 'engine');
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

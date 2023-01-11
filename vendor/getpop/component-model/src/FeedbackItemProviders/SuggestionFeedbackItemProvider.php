<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;
class SuggestionFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const S1 = 's1';
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::S1];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::S1:
                return $this->__('To execute multiple queries in a single request, add the following operation to the GraphQL query, and execute it: `query %s { id }`', 'component-model');
            default:
                return parent::getMessagePlaceholder($code);
        }
    }
    /**
     * @param string $code
     */
    public function getCategory($code) : string
    {
        return FeedbackCategories::SUGGESTION;
    }
}

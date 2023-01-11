<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;
class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = 'e1';
    public const E2 = 'e2';
    public const E3 = 'e3';
    public const E6 = 'e6';
    public const E7 = 'e7';
    public const E8 = 'e8';
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::E1, self::E2, self::E3, self::E6, self::E7, self::E8];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::E1:
                return $this->__('You must be logged in to create or update custom posts', 'custompost-mutations');
            case self::E2:
                return $this->__('Your user doesn\'t have permission for editing custom posts.', 'custompost-mutations');
            case self::E3:
                return $this->__('Your user doesn\'t have permission for publishing custom posts.', 'custompost-mutations');
            case self::E6:
                return $this->__('The custom post ID is missing', 'custompost-mutations');
            case self::E7:
                return $this->__('There is no custom post with ID \'%s\'', 'custompost-mutations');
            case self::E8:
                return $this->__('You don\'t have permission to edit custom post with ID \'%s\'', 'custompost-mutations');
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

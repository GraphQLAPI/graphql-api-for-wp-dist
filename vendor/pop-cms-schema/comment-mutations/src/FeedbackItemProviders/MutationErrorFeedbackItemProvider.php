<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;
class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = 'e1';
    public const E2 = 'e2';
    public const E3 = 'e3';
    public const E4 = 'e4';
    public const E5 = 'e5';
    public const E6 = 'e6';
    public const E7 = 'e7';
    public const E8 = 'e8';
    public const E9 = 'e9';
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::E1, self::E2, self::E3, self::E4, self::E5, self::E6, self::E7, self::E8, self::E9];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::E1:
                return $this->__('You are not logged in', 'user-state-mutations');
            case self::E2:
                return $this->__('The comment author\'s name is missing', 'comment-mutations');
            case self::E3:
                return $this->__('The comment author\'s email is missing', 'comment-mutations');
            case self::E4:
                return $this->__('The custom post ID is missing', 'comment-mutations');
            case self::E5:
                return $this->__('The comment is empty', 'comment-mutations');
            case self::E6:
                return $this->__('There is no (parent) comment with ID \'%s\'', 'comment-mutations');
            case self::E7:
                return $this->__('There is no custom post with ID \'%s\'', 'comment-mutations');
            case self::E8:
                return $this->__('Comments are not supported for custom post type \'%s\'', 'comment-mutations');
            case self::E9:
                return $this->__('Comments are not open for custom post with ID \'%s\'', 'comment-mutations');
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

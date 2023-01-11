<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;
class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = 'e1';
    public const E2 = 'e2';
    public const E3 = 'e3';
    public const E4 = 'e4';
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::E1, self::E2, self::E3, self::E4];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::E1:
                return $this->__('The media item ID is missing', 'custompostmedia-mutations');
            case self::E2:
                return $this->__('There is no media item with ID \'%s\'', 'custompostmedia-mutations');
            case self::E3:
                return $this->__('You must be logged in to set or remove the featured image on custom posts', 'custompost-mutations');
            case self::E4:
                return $this->__('Setting a featured image is not supported for custom post type \'%s\'', 'custompostmedia-mutations');
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

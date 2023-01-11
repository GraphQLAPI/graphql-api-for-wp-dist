<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\FeedbackItemProviders;

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
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::E1, self::E2, self::E3, self::E4, self::E5, self::E6, self::E7, self::E8];
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
                return $this->__('Please supply your username or email', 'user-state-mutations');
            case self::E3:
                return $this->__('Please supply your password', 'user-state-mutations');
            case self::E4:
                return $this->__('You are already logged in', 'user-state-mutations');
            case self::E5:
                return $this->__('No user is registered with username \'%s\'', 'user-state-mutations');
            case self::E6:
                return $this->__('No user is registered with email \'%s\'', 'user-state-mutations');
            case self::E7:
                return $this->__('The password is incorrect', 'user-state-mutations');
            case self::E8:
                return $this->__('[%1$s] %2$s', 'user-state-mutations');
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

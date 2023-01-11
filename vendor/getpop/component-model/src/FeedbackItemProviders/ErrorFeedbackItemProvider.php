<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;
class ErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E2 = 'e2';
    public const E3 = 'e3';
    public const E3A = 'e3a';
    public const E4 = 'e4';
    public const E5 = 'e5';
    public const E5A = 'e5a';
    public const E6 = 'e6';
    public const E6A = 'e6a';
    public const E7 = 'e7';
    public const E8 = 'e8';
    public const E9 = 'e9';
    public const E10 = 'e10';
    public const E11 = 'e11';
    public const E11A = 'e11a';
    public const E12 = 'e12';
    public const E15 = 'e15';
    public const E17 = 'e17';
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::E2, self::E3, self::E3A, self::E4, self::E5, self::E5A, self::E6, self::E6A, self::E7, self::E8, self::E9, self::E10, self::E11, self::E11A, self::E12, self::E15, self::E17];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::E2:
                return $this->__('There is no field \'%s\' on type \'%s\' satisfying version constraint \'%s\'', 'component-model');
            case self::E3:
                return $this->__('Resolving field \'%s\' produced error: \'%s\'', 'component-model');
            case self::E3A:
                return $this->__('Resolving field \'%s\' triggered exception: \'%s\'. Trace: %s', 'component-model');
            case self::E4:
                return $this->__('Resolving field \'%s\' triggered an exception, please contact the admin', 'component-model');
            case self::E5:
                return $this->__('Meta directive \'%s\' has no composed directives', 'component-model');
            case self::E5A:
                return $this->__('The directive pipeline for \'%s\' is empty', 'component-model');
            case self::E6:
                return $this->__('Resolving mutation \'%s\' produced error: \'%s\'', 'component-model');
            case self::E6A:
                return $this->__('Resolving mutation \'%s\' triggered exception: \'%s\'. Trace: %s', 'component-model');
            case self::E7:
                return $this->__('Resolving mutation \'%s\' triggered an exception, please contact the admin', 'component-model');
            case self::E8:
                return $this->__('No TypeResolver resolves the object', 'component-model');
            case self::E9:
                return $this->__('Data for object of type \'%s\' and ID \'%s\' cannot be loaded (possibly the DataLoader produced a wrong ID, or the data is corrupted)', 'component-model');
            case self::E10:
                return $this->__('In union type \'%s\', data for object with ID \'%s\' cannot be loaded (possibly the DataLoader produced a wrong ID, or the data is corrupted, or no TypeResolver in the Union can handle the type of this object)', 'component-model');
            case self::E11:
                return $this->__('Resolving directive \'%s\' produced error: \'%s\'', 'component-model');
            case self::E11A:
                return $this->__('Resolving directive \'%s\' triggered exception: \'%s\'. Trace: %s', 'component-model');
            case self::E12:
                return $this->__('Resolving directive \'%s\' triggered an exception, please contact the admin', 'component-model');
            case self::E15:
                return $this->__('For directive \'%s\', casting value \'%s\' for argument \'%s\' to type \'%s\' failed', 'component-model');
            case self::E17:
                return $this->__('For field \'%s\', casting value \'%s\' for argument \'%s\' to type \'%s\' failed', 'component-model');
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

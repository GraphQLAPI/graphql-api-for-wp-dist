<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;
class InputValueCoercionGraphQLSpecErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E_5_6_1_1 = '5.6.1[1]';
    public const E_5_6_1_2 = '5.6.1[2]';
    public const E_5_6_1_3 = '5.6.1[3]';
    public const E_5_6_1_4 = '5.6.1[4]';
    public const E_5_6_1_6 = '5.6.1[6]';
    public const E_5_6_1_7 = '5.6.1[7]';
    public const E_5_6_1_8 = '5.6.1[8]';
    public const E_5_6_1_9 = '5.6.1[9]';
    public const E_5_6_1_10 = '5.6.1[10]';
    public const E_5_6_1_11 = '5.6.1[11]';
    public const E_5_6_1_12 = '5.6.1[12]';
    public const E_5_6_1_13 = '5.6.1[13]';
    public const E_5_6_1_14 = '5.6.1[14]';
    public const E_5_6_1_15 = '5.6.1[15]';
    public const E_5_6_1_16 = '5.6.1[16]';
    public const E_5_6_1_17 = '5.6.1[17]';
    public const E_5_6_1_18 = '5.6.1[18]';
    public const E_5_6_1_19 = '5.6.1[19]';
    protected function getNamespace() : string
    {
        return 'gql';
    }
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::E_5_6_1_1, self::E_5_6_1_2, self::E_5_6_1_3, self::E_5_6_1_4, self::E_5_6_1_6, self::E_5_6_1_7, self::E_5_6_1_8, self::E_5_6_1_9, self::E_5_6_1_10, self::E_5_6_1_11, self::E_5_6_1_12, self::E_5_6_1_13, self::E_5_6_1_14, self::E_5_6_1_15, self::E_5_6_1_16, self::E_5_6_1_17, self::E_5_6_1_18, self::E_5_6_1_19];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::E_5_6_1_1:
                return $this->__('An object cannot be cast to type \'%s\'', 'component-model');
            case self::E_5_6_1_2:
                return $this->__('The format for \'%s\' is not correct for type \'%s\'', 'component-model');
            case self::E_5_6_1_3:
                return $this->__('Type \'%s\' must be provided as a string', 'component-model');
            case self::E_5_6_1_4:
                return $this->__('Argument \'%s\' of type \'%s\' cannot be `null`', 'component-model');
            case self::E_5_6_1_6:
                return $this->__('The oneof input object \'%s\' must receive exactly 1 input, but %s inputs were provided (\'%s\')', 'component-model');
            case self::E_5_6_1_7:
                return $this->__('No input value was provided to the oneof input object \'%s\'', 'component-model');
            case self::E_5_6_1_8:
                return $this->__('Argument \'%s\' does not expect an array, but array \'%s\' was provided', 'component-model');
            case self::E_5_6_1_9:
                return $this->__('Argument \'%s\' expects an array, but value \'%s\' was provided', 'component-model');
            case self::E_5_6_1_10:
                return $this->__('Argument \'%s\' cannot receive an array with `null` values', 'component-model');
            case self::E_5_6_1_11:
                return $this->__('Argument \'%s\' cannot receive an array containing arrays as elements', 'component-model');
            case self::E_5_6_1_12:
                return $this->__('Argument \'%s\' expects an array of arrays, but value \'%s\' was provided', 'component-model');
            case self::E_5_6_1_13:
                return $this->__('Argument \'%s\' cannot receive an array of arrays with `null` values', 'component-model');
            case self::E_5_6_1_14:
                return $this->__('Value \'%1$s\' for enum type \'%2$s\' is not valid (the only valid values are: \'%3$s\')', 'component-model');
            case self::E_5_6_1_15:
                return $this->__('Input object of type \'%s\' cannot be cast from input value \'%s\'', 'component-model');
            case self::E_5_6_1_16:
                return $this->__('Cannot cast value \'%s\' for type \'%s\'', 'component-model');
            case self::E_5_6_1_17:
                return $this->__('Only strings or integers are allowed for type \'%s\'', 'component-model');
            case self::E_5_6_1_18:
                return $this->__('Enum values can only be strings, value \'%s\' for type \'%s\' is not allowed', 'component-model');
            case self::E_5_6_1_19:
                return $this->__('Property \'%s\' in oneof input object \'%s\' cannot receive `null`', 'component-model');
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
    /**
     * @param string $code
     */
    public function getSpecifiedByURL($code) : ?string
    {
        return 'https://spec.graphql.org/draft/#sec-Values-of-Correct-Type';
    }
}

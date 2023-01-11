<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;
class GraphQLExtendedSpecErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = '1';
    public const E2 = '2';
    public const E3 = '3';
    public const E4 = '4';
    public const E5 = '5';
    public const E6 = '6';
    public const E7 = '7';
    public const E8 = '8';
    public const E9 = '9';
    public const E10 = '10';
    public const E11 = '11';
    public const E12 = '12';
    public const E13 = '13';
    public const E14 = '14';
    public const E15 = '15';
    public const E16 = '16';
    public const E_5_8_3 = '5.8.3';
    protected function getNamespace() : string
    {
        return 'gqlext';
    }
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::E1, self::E2, self::E3, self::E4, self::E5, self::E6, self::E7, self::E8, self::E9, self::E10, self::E11, self::E12, self::E13, self::E14, self::E15, self::E16, self::E_5_8_3];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::E1:
                return $this->__('Meta directive \'%s\' is nesting a directive already nested by another meta-directive', 'graphql-parser');
            case self::E2:
                return $this->__('Argument \'%1$s\' in directive \'%2$s\' cannot be null or empty', 'graphql-parser');
            case self::E3:
                return $this->__('Argument \'%1$s\' in directive \'%2$s\' must be an array of positive integers, array item \'%3$s\' is not allowed', 'graphql-parser');
            case self::E4:
                return $this->__('There is no directive in relative position \'%1$s\' from meta directive \'%2$s\', as indicated in argument \'%3$s\'', 'graphql-parser');
            case self::E5:
                return $this->__('There is no field in relative position \'%1$s\' from directive \'%2$s\', as indicated in argument \'%3$s\'', 'graphql-parser');
            case self::E6:
                return $this->__('The element in relative position \'%1$s\' from directive \'%2$s\' (as indicated in argument \'%3$s\') is not a field', 'graphql-parser');
            case self::E7:
                return $this->__('Dynamic variable \'%1$s\' cannot share the same name with a (static) variable', 'graphql-parser');
            case self::E8:
                return $this->__('The reference to the Resolved Field Value \'%1$s\' cannot share the same name with the variable \'%1$s\'', 'graphql-parser');
            case self::E9:
                return $this->__('Dynamic variable \'%1$s\' cannot share the same name with the reference to the Resolved Field Value \'%2$s\'', 'graphql-parser');
            case self::E10:
                return $this->__('No value has been exported for dynamic variable \'%s\' for object with ID \'%s\'', 'graphql-server');
            case self::E11:
                return $this->__('The reference to field \'%s\' cannot be resolved', 'graphql-server');
            case self::E12:
                return $this->__('The name of the operation must be a literal string', 'graphql-parser');
            case self::E13:
                return $this->__('The name of the operation must be a string, but \'%s\' was provided', 'graphql-parser');
            case self::E14:
                return $this->__('There is no operation with name \'%s\'', 'graphql-parser');
            case self::E15:
                return $this->__('Dependency on operation \'%s\' forms a loop', 'graphql-parser');
            case self::E16:
                return $this->__('No current object ID has been set on the Application State, hence the Promise concerning the \'Object Resolved Dynamic Variable "%s"\' cannot be resolved. Most likely the dynamic variable is not supported at that AST node', 'graphql-server');
            case self::E_5_8_3:
                return $this->__('No value has been exported for dynamic variable \'%s\'', 'graphql-server');
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

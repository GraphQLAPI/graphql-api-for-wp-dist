<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;
class GraphQLParserErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E_1 = '1';
    public const E_2 = '2';
    public const E_3 = '3';
    public const E_4 = '4';
    public const E_5 = '5';
    public const E_6 = '6';
    protected function getNamespace() : string
    {
        return 'gqlparser';
    }
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::E_1, self::E_2, self::E_3, self::E_4, self::E_5, self::E_6];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::E_1:
                return $this->__('Incorrect request syntax: %s', 'graphql-server');
            case self::E_2:
                return $this->__('Can\'t parse argument', 'graphql-parser');
            case self::E_3:
                return $this->__('Invalid string unicode escape sequece \'%s\'', 'graphql-server');
            case self::E_4:
                return $this->__('Unexpected string escaped character \'%s\'', 'graphql-server');
            case self::E_5:
                return $this->__('Can\\t recognize token type', 'graphql-server');
            case self::E_6:
                return $this->__('Unexpected token \'%s\'', 'graphql-server');
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
        return 'https://spec.graphql.org/draft/#sec-Language';
    }
}

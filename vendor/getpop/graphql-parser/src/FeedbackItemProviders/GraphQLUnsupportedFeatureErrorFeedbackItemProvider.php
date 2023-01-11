<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;
class GraphQLUnsupportedFeatureErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E_1 = '1';
    public const E_2 = '2';
    public const E_3 = '3';
    public const E_4 = '4';
    protected function getNamespace() : string
    {
        return 'gqlunsupported';
    }
    /**
     * @return string[]
     */
    public function getCodes() : array
    {
        return [self::E_1, self::E_2, self::E_3, self::E_4];
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        switch ($code) {
            case self::E_1:
                return $this->__('Subscriptions are currently not supported', 'graphql-server');
            case self::E_2:
                return $this->__('Fragment Definition Directives are currently not supported', 'graphql-server');
            case self::E_3:
                return $this->__('Variable Definition Directives are currently not supported', 'graphql-server');
            case self::E_4:
                return $this->__('Only up to 2 levels of List modifiers are supported (eg: `[[String]]`)', 'graphql-server');
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

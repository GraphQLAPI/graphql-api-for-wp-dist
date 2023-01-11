<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ComponentProcessors;

use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\Component\Component;
abstract class AbstractGraphQLRelationalFieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    protected function getRelationalFieldInnerComponent($component) : Component
    {
        return new Component(\GraphQLByPoP\GraphQLServer\ComponentProcessors\GraphQLRelationalFieldQueryDataComponentProcessor::class, \GraphQLByPoP\GraphQLServer\ComponentProcessors\GraphQLRelationalFieldQueryDataComponentProcessor::COMPONENT_LAYOUT_GRAPHQLRELATIONALFIELDS, $component->atts);
    }
}

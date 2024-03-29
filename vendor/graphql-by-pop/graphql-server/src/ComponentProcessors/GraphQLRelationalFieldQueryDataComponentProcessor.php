<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ComponentProcessors;

class GraphQLRelationalFieldQueryDataComponentProcessor extends \GraphQLByPoP\GraphQLServer\ComponentProcessors\AbstractGraphQLRelationalFieldQueryDataComponentProcessor
{
    public const COMPONENT_LAYOUT_GRAPHQLRELATIONALFIELDS = 'layout-graphqlrelationalfields';
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_LAYOUT_GRAPHQLRELATIONALFIELDS);
    }
}

<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ComponentRoutingProcessors;

use GraphQLByPoP\GraphQLServer\ComponentProcessors\SuperRootGraphQLRelationalFieldDataloadComponentProcessor;
use PoPAPI\API\Response\Schemes as APISchemes;
use PoPAPI\API\Routing\RequestNature;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor;
class EntryComponentRoutingProcessor extends AbstractEntryComponentRoutingProcessor
{
    /**
     * @var \PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter|null
     */
    private $graphQLDataStructureFormatter;
    /**
     * @param \PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter $graphQLDataStructureFormatter
     */
    public final function setGraphQLDataStructureFormatter($graphQLDataStructureFormatter) : void
    {
        $this->graphQLDataStructureFormatter = $graphQLDataStructureFormatter;
    }
    protected final function getGraphQLDataStructureFormatter() : GraphQLDataStructureFormatter
    {
        /** @var GraphQLDataStructureFormatter */
        return $this->graphQLDataStructureFormatter = $this->graphQLDataStructureFormatter ?? $this->instanceManager->getInstance(GraphQLDataStructureFormatter::class);
    }
    /**
     * @return array<string,array<array<string,mixed>>>
     */
    public function getStatePropertiesToSelectComponentByNature() : array
    {
        $ret = array();
        $ret[RequestNature::QUERY_ROOT][] = ['component' => new Component(SuperRootGraphQLRelationalFieldDataloadComponentProcessor::class, SuperRootGraphQLRelationalFieldDataloadComponentProcessor::COMPONENT_DATALOAD_RELATIONALFIELDS_SUPERROOT), 'conditions' => ['scheme' => APISchemes::API, 'datastructure' => $this->getGraphQLDataStructureFormatter()->getName()]];
        return $ret;
    }
}

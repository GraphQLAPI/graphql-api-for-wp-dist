<?php

declare (strict_types=1);
namespace PoPAPI\API\QueryParsing;

use PoP\GraphQLParser\Exception\Parser\LogicErrorParserException;
use PoP\GraphQLParser\Exception\FeatureNotSupportedException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorParserException;
use PoPAPI\API\ObjectModels\GraphQLQueryParsingPayload;
interface GraphQLParserHelperServiceInterface
{
    /**
     * @throws SyntaxErrorParserException
     * @throws FeatureNotSupportedException
     * @throws LogicErrorParserException
     * @param array<string,mixed> $variableValues
     * @param string $query
     * @param string|null $operationName
     */
    public function parseGraphQLQuery($query, $variableValues, $operationName) : GraphQLQueryParsingPayload;
}

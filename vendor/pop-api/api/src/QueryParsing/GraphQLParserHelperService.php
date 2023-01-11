<?php

declare (strict_types=1);
namespace PoPAPI\API\QueryParsing;

use PoPAPI\API\ObjectModels\GraphQLQueryParsingPayload;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\GraphQLParser\ExtendedSpec\Parser\Parser;
use PoP\GraphQLParser\Exception\Parser\LogicErrorParserException;
use PoP\GraphQLParser\Exception\FeatureNotSupportedException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorParserException;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\Root\Services\BasicServiceTrait;
class GraphQLParserHelperService implements \PoPAPI\API\QueryParsing\GraphQLParserHelperServiceInterface
{
    use BasicServiceTrait;
    /**
     * @throws SyntaxErrorParserException
     * @throws FeatureNotSupportedException
     * @throws LogicErrorParserException
     * @param array<string,mixed> $variableValues
     * @param string $query
     * @param string|null $operationName
     */
    public function parseGraphQLQuery($query, $variableValues, $operationName) : GraphQLQueryParsingPayload
    {
        $parser = $this->createParser();
        $document = $this->parseQuery($parser, $query);
        $executableDocument = new ExecutableDocument($document, new Context($operationName, $variableValues));
        return new GraphQLQueryParsingPayload($executableDocument, $parser->getObjectResolvedFieldValueReferencedFields());
    }
    protected function createParser() : ParserInterface
    {
        return new Parser();
    }
    /**
     * @param \PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface $parser
     * @param string $query
     */
    protected function parseQuery($parser, $query) : Document
    {
        return $parser->parse($query);
    }
}

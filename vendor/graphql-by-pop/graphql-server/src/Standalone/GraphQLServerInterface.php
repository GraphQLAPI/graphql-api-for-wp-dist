<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Standalone;

use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\Root\HttpFoundation\Response;
interface GraphQLServerInterface
{
    /**
     * Execute a GraphQL query, print the response in the buffer,
     * and send headers (eg: content-type => "application/json")
     *
     * @param array<string,mixed> $variables
     * @param string|\PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument $queryOrExecutableDocument
     * @param string|null $operationName
     */
    public function execute($queryOrExecutableDocument, $variables = [], $operationName = null) : Response;
}

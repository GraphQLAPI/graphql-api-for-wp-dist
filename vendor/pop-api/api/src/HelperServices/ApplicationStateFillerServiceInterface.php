<?php

declare (strict_types=1);
namespace PoPAPI\API\HelperServices;

use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
interface ApplicationStateFillerServiceInterface
{
    /**
     * Inject the GraphQL query AST and variables into
     * the app state.
     *
     * @param array<string,mixed> $variables
     * @param string|\PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument $queryOrExecutableDocument
     * @param string|null $operationName
     */
    public function defineGraphQLQueryVarsInApplicationState($queryOrExecutableDocument, $variables = [], $operationName = null) : void;
}

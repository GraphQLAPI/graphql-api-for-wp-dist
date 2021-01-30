<?php

/**
 * Date: 10/24/16
 *
 * @author Portey Vasil <portey@gmail.com>
 */
namespace GraphQLByPoP\GraphQLParser\Validator\RequestValidator;

use GraphQLByPoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use GraphQLByPoP\GraphQLParser\Execution\Request;
class RequestValidator implements \GraphQLByPoP\GraphQLParser\Validator\RequestValidator\RequestValidatorInterface
{
    public function validate(\GraphQLByPoP\GraphQLParser\Execution\Request $request)
    {
        $this->assertFragmentReferencesValid($request);
        $this->assetFragmentsUsed($request);
        $this->assertAllVariablesExists($request);
        $this->assertAllVariablesUsed($request);
    }
    private function assetFragmentsUsed(\GraphQLByPoP\GraphQLParser\Execution\Request $request)
    {
        foreach ($request->getFragmentReferences() as $fragmentReference) {
            $request->getFragment($fragmentReference->getName())->setUsed(\true);
        }
        foreach ($request->getFragments() as $fragment) {
            if (!$fragment->isUsed()) {
                throw new \GraphQLByPoP\GraphQLParser\Exception\Parser\InvalidRequestException(\sprintf('Fragment "%s" not used', $fragment->getName()), $fragment->getLocation());
            }
        }
    }
    private function assertFragmentReferencesValid(\GraphQLByPoP\GraphQLParser\Execution\Request $request)
    {
        foreach ($request->getFragmentReferences() as $fragmentReference) {
            if (!$request->getFragment($fragmentReference->getName())) {
                throw new \GraphQLByPoP\GraphQLParser\Exception\Parser\InvalidRequestException(\sprintf('Fragment "%s" not defined in query', $fragmentReference->getName()), $fragmentReference->getLocation());
            }
        }
    }
    private function assertAllVariablesExists(\GraphQLByPoP\GraphQLParser\Execution\Request $request)
    {
        foreach ($request->getVariableReferences() as $variableReference) {
            if (!$variableReference->getVariable()) {
                throw new \GraphQLByPoP\GraphQLParser\Exception\Parser\InvalidRequestException(\sprintf('Variable "%s" not exists', $variableReference->getName()), $variableReference->getLocation());
            }
        }
    }
    private function assertAllVariablesUsed(\GraphQLByPoP\GraphQLParser\Execution\Request $request)
    {
        foreach ($request->getQueryVariables() as $queryVariable) {
            if (!$queryVariable->isUsed()) {
                throw new \GraphQLByPoP\GraphQLParser\Exception\Parser\InvalidRequestException(\sprintf('Variable "%s" not used', $queryVariable->getName()), $queryVariable->getLocation());
            }
        }
    }
}

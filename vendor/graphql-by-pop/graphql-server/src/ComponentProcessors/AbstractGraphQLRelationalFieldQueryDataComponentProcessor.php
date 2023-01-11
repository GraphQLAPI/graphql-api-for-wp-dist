<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ComponentProcessors;

use GraphQLByPoP\GraphQLServer\QueryResolution\GraphQLQueryASTTransformationServiceInterface;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldQueryDataComponentProcessor;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use SplObjectStorage;
abstract class AbstractGraphQLRelationalFieldQueryDataComponentProcessor extends AbstractRelationalFieldQueryDataComponentProcessor
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\QueryResolution\GraphQLQueryASTTransformationServiceInterface|null
     */
    private $graphQLQueryASTTransformationService;
    /**
     * @param \GraphQLByPoP\GraphQLServer\QueryResolution\GraphQLQueryASTTransformationServiceInterface $graphQLQueryASTTransformationService
     */
    public final function setGraphQLQueryASTTransformationService($graphQLQueryASTTransformationService) : void
    {
        $this->graphQLQueryASTTransformationService = $graphQLQueryASTTransformationService;
    }
    protected final function getGraphQLQueryASTTransformationService() : GraphQLQueryASTTransformationServiceInterface
    {
        /** @var GraphQLQueryASTTransformationServiceInterface */
        return $this->graphQLQueryASTTransformationService = $this->graphQLQueryASTTransformationService ?? $this->instanceManager->getInstance(GraphQLQueryASTTransformationServiceInterface::class);
    }
    /**
     * Convert the operations to include the SuperRoot Fields
     *
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>>
     * @param \PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument $executableDocument
     */
    protected function getOperationFieldOrFragmentBonds($executableDocument) : SplObjectStorage
    {
        $document = $executableDocument->getDocument();
        /** @var OperationInterface[] */
        $operations = $executableDocument->getMultipleOperationsToExecute();
        return $this->getGraphQLQueryASTTransformationService()->prepareOperationFieldAndFragmentBondsForExecution($document, $operations, $document->getFragments());
    }
}

<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\Root\Services\StandaloneServiceTrait;
use PoP\GraphQLParser\Exception\RuntimeVariableReferenceException;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DocumentDynamicVariableReference;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
class DocumentDynamicVariableValuePromise implements \PoP\GraphQLParser\ExtendedSpec\Execution\ValueResolutionPromiseInterface
{
    use StandaloneServiceTrait;
    /**
     * @readonly
     * @var \PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DocumentDynamicVariableReference
     */
    public $documentDynamicVariableReference;
    public function __construct(DocumentDynamicVariableReference $documentDynamicVariableReference)
    {
        $this->documentDynamicVariableReference = $documentDynamicVariableReference;
    }
    /**
     * @throws RuntimeVariableReferenceException When accessing non-declared Dynamic Variables
     * @return mixed
     */
    public function resolveValue()
    {
        /** @var array<string,mixed> */
        $documentDynamicVariables = App::getState('document-dynamic-variables');
        $dynamicVariableName = $this->documentDynamicVariableReference->getName();
        if (!\array_key_exists($dynamicVariableName, $documentDynamicVariables)) {
            // Variable is nowhere defined => Error
            throw new RuntimeVariableReferenceException(new FeedbackItemResolution(GraphQLExtendedSpecErrorFeedbackItemProvider::class, GraphQLExtendedSpecErrorFeedbackItemProvider::E_5_8_3, [$this->documentDynamicVariableReference->getName()]), $this->documentDynamicVariableReference);
        }
        return $documentDynamicVariables[$dynamicVariableName];
    }
    /**
     * The field/directiveArgs containing the promise must be resolved:
     *
     * Only once during the Engine Iteration for all involved fields/objects
     */
    public function mustResolveOnObject() : bool
    {
        return \false;
    }
}

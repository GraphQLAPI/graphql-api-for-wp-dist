<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Exception\ObjectFieldValuePromiseException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLExtendedSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\StandaloneServiceTrait;
use SplObjectStorage;
class ObjectFieldValuePromise implements \PoP\GraphQLParser\ExtendedSpec\Execution\ValueResolutionPromiseInterface
{
    use StandaloneServiceTrait;
    /**
     * @readonly
     * @var \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface
     */
    public $field;
    public function __construct(FieldInterface $field)
    {
        $this->field = $field;
    }
    /**
     * @return mixed
     */
    public function resolveValue()
    {
        /** @var SplObjectStorage<FieldInterface,mixed> */
        $objectResolvedFieldValues = App::getState('engine-iteration-object-resolved-field-values');
        if (!$objectResolvedFieldValues->contains($this->field)) {
            throw new ObjectFieldValuePromiseException(new FeedbackItemResolution(GraphQLExtendedSpecErrorFeedbackItemProvider::class, GraphQLExtendedSpecErrorFeedbackItemProvider::E11, [$this->field->asFieldOutputQueryString()]), $this->field);
        }
        return $objectResolvedFieldValues[$this->field];
    }
    /**
     * The field/directiveArgs containing the promise must be resolved:
     *
     * Object by object
     */
    public function mustResolveOnObject() : bool
    {
        return \true;
    }
}

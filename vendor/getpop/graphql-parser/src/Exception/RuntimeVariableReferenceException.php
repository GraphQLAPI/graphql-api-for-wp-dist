<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Exception;

use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\RuntimeVariableReferenceInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
final class RuntimeVariableReferenceException extends \PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException
{
    /**
     * @readonly
     * @var \PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\RuntimeVariableReferenceInterface
     */
    private $runtimeVariableReference;
    public function __construct(FeedbackItemResolution $feedbackItemResolution, RuntimeVariableReferenceInterface $runtimeVariableReference)
    {
        $this->runtimeVariableReference = $runtimeVariableReference;
        parent::__construct($feedbackItemResolution, $runtimeVariableReference);
    }
    public function getRuntimeVariableReference() : RuntimeVariableReferenceInterface
    {
        return $this->runtimeVariableReference;
    }
}

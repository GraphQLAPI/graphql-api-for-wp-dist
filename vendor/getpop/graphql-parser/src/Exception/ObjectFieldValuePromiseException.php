<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Exception;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
final class ObjectFieldValuePromiseException extends \PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException
{
    /**
     * @readonly
     * @var \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface
     */
    private $field;
    public function __construct(FeedbackItemResolution $feedbackItemResolution, FieldInterface $field)
    {
        $this->field = $field;
        parent::__construct($feedbackItemResolution, $field);
    }
    public function getField() : FieldInterface
    {
        return $this->field;
    }
}

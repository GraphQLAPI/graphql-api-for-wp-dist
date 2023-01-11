<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;
/**
 * Special scalar type which is not coerced or validated.
 * In particular, it does not need to validate if it is an array or not,
 * as according to the applied WrappingType.
 *
 * This is to enable it to have an array as value, which is not
 * allowed by GraphQL unless the array is explicitly defined.
 *
 * For instance, type `DangerouslyNonSpecificScalar` could have values
 * `"hello"` and `["hello"]`, but in GraphQL we must differentiate
 * these values by types `String` and `[String]`.
 */
class DangerouslyNonSpecificScalarTypeScalarTypeResolver extends \PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver
{
    public function getTypeName() : string
    {
        return 'DangerouslyNonSpecificScalar';
    }
    /**
     * This method will never be called for DangerouslyNonSpecificScalar
     * @param string|int|float|bool|\stdClass $inputValue
     * @return string|int|float|bool|object|null
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function coerceValue($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore)
    {
        return $inputValue;
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Special scalar type which is not coerced or validated. In particular, it does not need to validate if it is an array or not, as GraphQL requires based on the applied WrappingType (such as `[String]`).', 'component-model');
    }
}

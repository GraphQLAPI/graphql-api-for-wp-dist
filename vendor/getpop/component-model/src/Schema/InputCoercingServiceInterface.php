<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
interface InputCoercingServiceInterface
{
    /**
     * Support passing a single value where a list is expected:
     * `{ posts(ids: 1) }` means `{ posts(ids: [1]) }`
     *
     * Defined in the GraphQL spec.
     *
     * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
     * @param mixed $inputValue
     * @return mixed
     * @param bool $inputIsArrayType
     * @param bool $inputIsArrayOfArraysType
     */
    public function maybeConvertInputValueFromSingleToList($inputValue, $inputIsArrayType, $inputIsArrayOfArraysType);
    /**
     * Validate that the expected array/non-array input is provided,
     * checking that the WrappingType is respected.
     *
     * Nullable booleans can be `null` for the DangerouslyNonSpecificScalar,
     * so they can also validate their cardinality:
     *
     *   - DangerouslyNonSpecificScalar does not need to validate anything => all null
     *   - [DangerouslyNonSpecificScalar] must certainly be an array, but it doesn't care
     *     inside if it's an array or not => $inputIsArrayType => true, $inputIsArrayOfArraysType => null
     *   - [[DangerouslyNonSpecificScalar]] must be array of arrays => $inputIsArrayType => true, $inputIsArrayOfArraysType => true
     *
     * Eg: `["hello"]` must be `[String]`, can't be `[[String]]` or `String`.
     * @param mixed $inputValue
     * @param \PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface $inputTypeResolver
     * @param string $inputName
     * @param bool|null $inputIsArrayType
     * @param bool|null $inputIsNonNullArrayItemsType
     * @param bool|null $inputIsArrayOfArraysType
     * @param bool|null $inputIsNonNullArrayOfArraysItemsType
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validateInputArrayModifiers($inputTypeResolver, $inputValue, $inputName, $inputIsArrayType, $inputIsNonNullArrayItemsType, $inputIsArrayOfArraysType, $inputIsNonNullArrayOfArraysItemsType, $astNode, $objectTypeFieldResolutionFeedbackStore) : void;
    /**
     * Coerce the input value, corresponding to the array type
     * defined by the modifiers.
     * @param mixed $inputValue
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface $inputTypeResolver
     * @param string $inputName
     * @param bool $inputIsNonNullable
     * @param bool $inputIsArrayType
     * @param bool $inputIsArrayOfArraysType
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function coerceInputValue($inputTypeResolver, $inputValue, $inputName, $inputIsNonNullable, $inputIsArrayType, $inputIsArrayOfArraysType, $astNode, $objectTypeFieldResolutionFeedbackStore);
    /**
     * If applicable, get the deprecation messages for the input value
     *
     * @return string[]
     * @param mixed $inputValue
     * @param \PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface $inputTypeResolver
     * @param bool $inputIsArrayType
     * @param bool $inputIsArrayOfArraysType
     */
    public function getInputValueDeprecationMessages($inputTypeResolver, $inputValue, $inputIsArrayType, $inputIsArrayOfArraysType) : array;
}

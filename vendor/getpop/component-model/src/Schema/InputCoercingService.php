<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\InputValueCoercionGraphQLSpecErrorFeedbackItemProvider;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\Response\OutputServiceInterface;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\GraphQLParser\ExtendedSpec\Execution\ValueResolutionPromiseInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;
use stdClass;
class InputCoercingService implements \PoP\ComponentModel\Schema\InputCoercingServiceInterface
{
    use BasicServiceTrait;
    /**
     * @var \PoP\ComponentModel\Response\OutputServiceInterface|null
     */
    private $outputService;
    /**
     * @param \PoP\ComponentModel\Response\OutputServiceInterface $outputService
     */
    public final function setOutputService($outputService) : void
    {
        $this->outputService = $outputService;
    }
    protected final function getOutputService() : OutputServiceInterface
    {
        /** @var OutputServiceInterface */
        return $this->outputService = $this->outputService ?? $this->instanceManager->getInstance(OutputServiceInterface::class);
    }
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
    public function maybeConvertInputValueFromSingleToList($inputValue, $inputIsArrayType, $inputIsArrayOfArraysType)
    {
        /**
         * If it is a Promise then don't convert it, since its underlying
         * value may actually be an array, but we don't know it yet.
         */
        if ($inputValue instanceof ValueResolutionPromiseInterface) {
            return $inputValue;
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (\is_array($inputValue) || !$moduleConfiguration->convertInputValueFromSingleToList()) {
            return $inputValue;
        }
        if ($inputIsArrayOfArraysType) {
            return [[$inputValue]];
        }
        if ($inputIsArrayType) {
            return [$inputValue];
        }
        return $inputValue;
    }
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
    public function validateInputArrayModifiers($inputTypeResolver, $inputValue, $inputName, $inputIsArrayType, $inputIsNonNullArrayItemsType, $inputIsArrayOfArraysType, $inputIsNonNullArrayOfArraysItemsType, $astNode, $objectTypeFieldResolutionFeedbackStore) : void
    {
        /**
         * If the type is `DangerouslyNonSpecificScalar`, there's nothing
         * to validate
         */
        if ($inputIsArrayType === null && $inputIsArrayOfArraysType === null) {
            return;
        }
        /**
         * If it is a Promise then don't convert it, since its underlying
         * value may actually be an array, but we don't know it yet.
         */
        if ($inputValue instanceof ValueResolutionPromiseInterface) {
            return;
        }
        if ($inputIsArrayType === \false && \is_array($inputValue)) {
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class, InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_8, [$inputName, \json_encode($inputValue)]), $astNode));
            return;
        }
        if ($inputIsArrayType && !\is_array($inputValue)) {
            $inputValueAsString = $inputValue instanceof stdClass ? $this->getOutputService()->jsonEncodeArrayOrStdClassValue($inputValue) : $inputValue;
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class, InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_9, [$inputName, $inputValueAsString]), $astNode));
            return;
        }
        if ($inputIsNonNullArrayItemsType && \is_array($inputValue) && \array_filter($inputValue, function ($arrayItem) {
            return $arrayItem === null;
        })) {
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class, InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_10, [$inputName]), $astNode));
            return;
        }
        if ($inputIsArrayType && $inputIsArrayOfArraysType === \false && \array_filter($inputValue, function ($arrayItem) {
            return \is_array($arrayItem);
        })) {
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class, InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_11, [$inputName, \json_encode($inputValue)]), $astNode));
            return;
        }
        if ($inputIsArrayOfArraysType && \is_array($inputValue) && \array_filter(
            $inputValue,
            // `null` could be accepted as an array! (Validation against null comes next)
            function ($arrayItem) {
                return !\is_array($arrayItem) && $arrayItem !== null && !$arrayItem instanceof ValueResolutionPromiseInterface;
            }
        )) {
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class, InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_12, [$inputName, \json_encode($inputValue)]), $astNode));
            return;
        }
        if ($inputIsNonNullArrayOfArraysItemsType && \is_array($inputValue) && \array_filter($inputValue, function (?array $arrayItem) {
            return $arrayItem === null ? \false : \array_filter($arrayItem, function ($arrayItemItem) {
                return $arrayItemItem === null;
            }) !== [];
        })) {
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class, InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_13, [$inputName]), $astNode));
            return;
        }
    }
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
    public function coerceInputValue($inputTypeResolver, $inputValue, $inputName, $inputIsNonNullable, $inputIsArrayType, $inputIsArrayOfArraysType, $astNode, $objectTypeFieldResolutionFeedbackStore)
    {
        /**
         * If it is a null, validate the input can be nullable
         */
        if ($inputValue === null && $inputIsNonNullable) {
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class, InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_4, [$inputName, $inputTypeResolver->getMaybeNamespacedTypeName()]), $astNode));
            return null;
        }
        /**
         * If it is a null, nothing to coerce.
         *
         * If it is a ValueResolutionPromiseInterface, then we don't have the
         * value yet, so it can't be coerced.
         *
         * This has the implications that ObjectFieldValueReference
         * are not coerced!!! The output must already be the same
         * type as the input, and there's no validation to enforce it.
         */
        if ($inputValue === null || $inputValue instanceof ValueResolutionPromiseInterface) {
            return $inputValue;
        }
        if ($inputIsArrayOfArraysType) {
            /** @var array<mixed[]> $inputValue */
            // If the value is an array of arrays, then cast each subelement to the item type
            return \array_map(
                // If it contains a null value, return it as is
                function ($arrayArgValueElem) use($inputTypeResolver, $astNode, $objectTypeFieldResolutionFeedbackStore) {
                    return $arrayArgValueElem === null || $arrayArgValueElem instanceof ValueResolutionPromiseInterface ? $arrayArgValueElem : \array_map(function ($arrayOfArraysArgValueElem) use($inputTypeResolver, $astNode, $objectTypeFieldResolutionFeedbackStore) {
                        return $arrayOfArraysArgValueElem === null || $arrayOfArraysArgValueElem instanceof ValueResolutionPromiseInterface ? $arrayOfArraysArgValueElem : $inputTypeResolver->coerceValue($arrayOfArraysArgValueElem, $astNode, $objectTypeFieldResolutionFeedbackStore);
                    }, $arrayArgValueElem);
                },
                $inputValue
            );
        }
        if ($inputIsArrayType) {
            /** @var mixed[] $inputValue */
            // If the value is an array, then cast each element to the item type
            return \array_map(function ($arrayArgValueElem) use($inputTypeResolver, $astNode, $objectTypeFieldResolutionFeedbackStore) {
                return $arrayArgValueElem === null || $arrayArgValueElem instanceof ValueResolutionPromiseInterface ? $arrayArgValueElem : $inputTypeResolver->coerceValue($arrayArgValueElem, $astNode, $objectTypeFieldResolutionFeedbackStore);
            }, $inputValue);
        }
        // Otherwise, simply cast the given value directly
        return $inputTypeResolver->coerceValue($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * If applicable, get the deprecation messages for the input value
     *
     * @return string[]
     * @param mixed $inputValue
     * @param \PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface $deprecatableInputTypeResolver
     * @param bool $inputIsArrayType
     * @param bool $inputIsArrayOfArraysType
     */
    public function getInputValueDeprecationMessages($deprecatableInputTypeResolver, $inputValue, $inputIsArrayType, $inputIsArrayOfArraysType) : array
    {
        if ($inputValue === null || $inputValue instanceof ValueResolutionPromiseInterface) {
            return [];
        }
        $inputValueDeprecations = [];
        if ($inputIsArrayOfArraysType) {
            // Execute against an array of arrays of values
            foreach ($inputValue as $arrayArgValueElem) {
                if ($arrayArgValueElem === null || $arrayArgValueElem instanceof ValueResolutionPromiseInterface) {
                    continue;
                }
                foreach ($arrayArgValueElem as $arrayOfArraysArgValueElem) {
                    if ($arrayOfArraysArgValueElem === null || $arrayOfArraysArgValueElem instanceof ValueResolutionPromiseInterface) {
                        continue;
                    }
                    $inputValueDeprecations = \array_merge($inputValueDeprecations, $deprecatableInputTypeResolver->getInputValueDeprecationMessages($arrayOfArraysArgValueElem));
                }
            }
        } elseif ($inputIsArrayType) {
            // Execute against an array of values
            foreach ($inputValue as $arrayArgValueElem) {
                if ($arrayArgValueElem === null || $arrayArgValueElem instanceof ValueResolutionPromiseInterface) {
                    continue;
                }
                $inputValueDeprecations = \array_merge($inputValueDeprecations, $deprecatableInputTypeResolver->getInputValueDeprecationMessages($arrayArgValueElem));
            }
        } else {
            // Execute against the single value
            $inputValueDeprecations = $deprecatableInputTypeResolver->getInputValueDeprecationMessages($inputValue);
        }
        return \array_unique($inputValueDeprecations);
    }
}

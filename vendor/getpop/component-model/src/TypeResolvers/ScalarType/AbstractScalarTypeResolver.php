<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\InputValueCoercionGraphQLSpecErrorFeedbackItemProvider;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use stdClass;
abstract class AbstractScalarTypeResolver extends AbstractTypeResolver implements \PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface
{
    /**
     * @var \PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface|null
     */
    private $objectSerializationManager;
    /**
     * @param \PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface $objectSerializationManager
     */
    public final function setObjectSerializationManager($objectSerializationManager) : void
    {
        $this->objectSerializationManager = $objectSerializationManager;
    }
    protected final function getObjectSerializationManager() : ObjectSerializationManagerInterface
    {
        /** @var ObjectSerializationManagerInterface */
        return $this->objectSerializationManager = $this->objectSerializationManager ?? $this->instanceManager->getInstance(ObjectSerializationManagerInterface::class);
    }
    public function getSpecifiedByURL() : ?string
    {
        return null;
    }
    /**
     * @return string|int|float|bool|mixed[]|stdClass
     * @param string|int|float|bool|object $scalarValue
     */
    public function serialize($scalarValue)
    {
        /**
         * Convert object to string or stdClass and,
         * in the latter case, it will be serialized yet again
         */
        if (\is_object($scalarValue) && !$scalarValue instanceof stdClass) {
            $scalarValue = $this->getObjectSerializationManager()->serialize($scalarValue);
        }
        /**
         * Convert stdClass to array, and apply recursively
         * (i.e. if some stdClass property is stdClass or object)
         */
        if ($scalarValue instanceof stdClass) {
            return (object) \array_map(function ($scalarValueArrayElem) {
                if ($scalarValueArrayElem === null) {
                    return null;
                }
                if (\is_array($scalarValueArrayElem)) {
                    // Convert from array to stdClass and back
                    return (array) $this->serialize((object) $scalarValueArrayElem);
                }
                return $this->serialize($scalarValueArrayElem);
            }, (array) $scalarValue);
        }
        // Return as is
        return $scalarValue;
    }
    /**
     * @param string|int|float|bool|\stdClass $inputValue
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    protected final function validateIsNotStdClass($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore) : void
    {
        if (!$inputValue instanceof stdClass) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class, InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_1, [$this->getMaybeNamespacedTypeName()]), $astNode));
    }
    /**
     * @param array<string,mixed>|int $options
     * @param mixed $inputValue
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     * @param int $filter
     */
    protected final function validateFilterVar($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore, $filter, $options = []) : void
    {
        $valid = \filter_var($inputValue, $filter, $options);
        if ($valid !== \false) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class, InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_2, [$inputValue, $this->getMaybeNamespacedTypeName()]), $astNode));
    }
    /**
     * @param string|int|float|bool|\stdClass $inputValue
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    protected final function validateIsString($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore) : void
    {
        if (\is_string($inputValue)) {
            return;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class, InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_3, [$this->getMaybeNamespacedTypeName()]), $astNode));
    }
    /**
     * @param mixed $inputValue
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    protected function addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore) : void
    {
        $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class, InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_16, [$inputValue, $this->getMaybeNamespacedTypeName()]), $astNode));
    }
}

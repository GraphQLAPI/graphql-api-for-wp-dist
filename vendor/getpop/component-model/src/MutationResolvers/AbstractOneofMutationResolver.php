<?php

declare (strict_types=1);
namespace PoP\ComponentModel\MutationResolvers;

use PoP\ComponentModel\Exception\QueryResolutionException;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\QueryResolution\InputObjectSubpropertyFieldDataAccessor;
use PoP\ComponentModel\QueryResolution\InputObjectSubpropertyFieldDataAccessorInterface;
use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP\Root\Feedback\FeedbackItemResolution;
use stdClass;
abstract class AbstractOneofMutationResolver extends \PoP\ComponentModel\MutationResolvers\AbstractMutationResolver
{
    /** @var array<string,MutationResolverInterface>|null */
    private $consolidatedInputFieldNameMutationResolversCache;
    /**
     * The MutationResolvers contained in the OneofMutationResolver,
     * organized by inputFieldName
     *
     * @return array<string,MutationResolverInterface> Array of inputFieldName => MutationResolver
     */
    protected abstract function getInputFieldNameMutationResolvers() : array;
    /**
     * Consolidation of the mutation resolver for each input field. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @return array<string,MutationResolverInterface>
     */
    public final function getConsolidatedInputFieldNameMutationResolvers() : array
    {
        if ($this->consolidatedInputFieldNameMutationResolversCache !== null) {
            return $this->consolidatedInputFieldNameMutationResolversCache;
        }
        $this->consolidatedInputFieldNameMutationResolversCache = App::applyFilters(\PoP\ComponentModel\MutationResolvers\HookNames::INPUT_FIELD_NAME_MUTATION_RESOLVERS, $this->getInputFieldNameMutationResolvers(), $this);
        return $this->consolidatedInputFieldNameMutationResolversCache;
    }
    /**
     * The oneof input object can receive only 1 input field at a time.
     * Retrieve it, or throw an Exception if this is not respected
     *
     * @throws QueryResolutionException If either there is none or more than 1 inputFieldNames being used
     * @param \stdClass $oneofInputObjectFormData
     */
    protected function getCurrentInputFieldName($oneofInputObjectFormData) : string
    {
        $oneofInputObjectFormDataSize = \count((array) $oneofInputObjectFormData);
        if ($oneofInputObjectFormDataSize !== 1) {
            throw new QueryResolutionException(\sprintf($this->__('Only and exactly 1 input field must be provided to the OneofMutationResolver, but %s were provided', 'component-model'), $oneofInputObjectFormDataSize));
        }
        // Retrieve the first (and only) element key
        return (string) \key((array) $oneofInputObjectFormData);
    }
    /**
     * @throws QueryResolutionException If there is not MutationResolver for the input field
     * @param string $inputFieldName
     */
    protected function getInputFieldMutationResolver($inputFieldName) : \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
    {
        $inputFieldMutationResolver = $this->getConsolidatedInputFieldNameMutationResolvers()[$inputFieldName] ?? null;
        if ($inputFieldMutationResolver === null) {
            throw new QueryResolutionException(\sprintf($this->__('There is no MutationResolver for input field with name \'%s\'', 'component-model'), $inputFieldName));
        }
        return $inputFieldMutationResolver;
    }
    /**
     * Assume there's only one argument in the field,
     * for this OneofMutationResolver.
     * If that's not the case, this function must be overriden,
     * to avoid throwing an Exception
     *
     * @throws QueryResolutionException If more than 1 argument is passed to the field executing the OneofMutation
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function getOneofInputObjectPropertyName($fieldDataAccessor) : string
    {
        $propertyNames = $fieldDataAccessor->getProperties();
        $formDataSize = \count($propertyNames);
        if ($formDataSize !== 1) {
            throw new QueryResolutionException(\sprintf($this->__('The OneofMutationResolver expects only 1 argument is passed to the field executing the mutation, but %s were provided: \'%s\'', 'component-model'), $formDataSize, \implode($this->__(', ', 'component-model'), $propertyNames)));
        }
        return $propertyNames[0];
    }
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @throws AbstractException In case of error
     * @return mixed
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function executeMutation($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        [$inputFieldMutationResolver, $fieldDataAccessor] = $this->getInputFieldMutationResolverAndOneOfFieldDataAccessor($fieldDataAccessor);
        /** @var MutationResolverInterface $inputFieldMutationResolver */
        return $inputFieldMutationResolver->executeMutation($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @throws AbstractValueResolutionPromiseException
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        try {
            [$inputFieldMutationResolver, $fieldDataAccessor] = $this->getInputFieldMutationResolverAndOneOfFieldDataAccessor($fieldDataAccessor);
            /** @var MutationResolverInterface $inputFieldMutationResolver */
            $inputFieldMutationResolver->validate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        } catch (QueryResolutionException $e) {
            // Return the error message from the exception
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E1, [$e->getMessage()]), $fieldDataAccessor->getField()));
        }
    }
    /**
     * @return mixed[] An array of 2 items: the current input field's mutation resolver, and the AST with the current input field's form data
     * @throws QueryResolutionException If there is not MutationResolver for the input field
     * @throws AbstractValueResolutionPromiseException
     * @param \PoP\ComponentModel\QueryResolution\InputObjectSubpropertyFieldDataAccessorInterface $inputObjectFieldArgumentFieldDataAccessor
     */
    protected final function getInputFieldMutationResolverAndOneOfFieldDataAccessor($inputObjectFieldArgumentFieldDataAccessor) : array
    {
        // Create a new Field, passing the corresponding Argument only
        $oneOfPropertyName = $this->getOneofInputObjectPropertyName($inputObjectFieldArgumentFieldDataAccessor);
        $inputFieldMutationResolver = $this->getInputFieldMutationResolver($oneOfPropertyName);
        $oneOfFieldDataAccessor = $this->getOneOfFieldDataAccessor($inputObjectFieldArgumentFieldDataAccessor, $oneOfPropertyName);
        return [$inputFieldMutationResolver, $oneOfFieldDataAccessor];
    }
    /**
     * @throws AbstractValueResolutionPromiseException
     * @param \PoP\ComponentModel\QueryResolution\InputObjectSubpropertyFieldDataAccessorInterface $inputObjectFieldArgumentFieldDataAccessor
     * @param string $oneOfPropertyName
     */
    protected final function getOneOfFieldDataAccessor($inputObjectFieldArgumentFieldDataAccessor, $oneOfPropertyName) : InputObjectSubpropertyFieldDataAccessorInterface
    {
        return new InputObjectSubpropertyFieldDataAccessor($inputObjectFieldArgumentFieldDataAccessor->getField(), $oneOfPropertyName, $inputObjectFieldArgumentFieldDataAccessor->getFieldArgs());
    }
}

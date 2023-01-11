<?php

declare (strict_types=1);
namespace PoP\ComponentModel\MutationResolverBridges;

use Exception;
use PoP\ComponentModel\App;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\MutationResolvers\ErrorTypes;
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;
use PoP\ComponentModel\QueryResolution\FieldDataAccessor;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\Root\Exception\AbstractClientException;
use PoP\Root\Services\BasicServiceTrait;
abstract class AbstractComponentMutationResolverBridge implements \PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface
{
    use BasicServiceTrait;
    /**
     * @var \PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface|null
     */
    private $componentProcessorManager;
    /**
     * @param \PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface $componentProcessorManager
     */
    public final function setComponentProcessorManager($componentProcessorManager) : void
    {
        $this->componentProcessorManager = $componentProcessorManager;
    }
    protected final function getComponentProcessorManager() : ComponentProcessorManagerInterface
    {
        /** @var ComponentProcessorManagerInterface */
        return $this->componentProcessorManager = $this->componentProcessorManager ?? $this->instanceManager->getInstance(ComponentProcessorManagerInterface::class);
    }
    /**
     * @param string|int $resultID
     */
    public function getSuccessString($resultID) : ?string
    {
        return null;
    }
    /**
     * @return string[]
     * @param string|int $resultID
     */
    public function getSuccessStrings($resultID) : array
    {
        $success_string = $this->getSuccessString($resultID);
        return $success_string !== null ? [$success_string] : [];
    }
    protected function onlyExecuteWhenDoingPost() : bool
    {
        return \true;
    }
    protected function skipDataloadIfError() : bool
    {
        return \false;
    }
    /**
     * @return array<string,mixed>|null
     * @param array<string,mixed> $data_properties
     */
    public function executeMutation(&$data_properties) : ?array
    {
        if ($this->onlyExecuteWhenDoingPost() && 'POST' !== App::server('REQUEST_METHOD')) {
            return null;
        }
        $mutationResolver = $this->getMutationResolver();
        $fieldDataAccessorForMutation = $this->getFieldDataAccessorForMutation();
        $mutationResponse = [];
        // Validate errors
        $errorType = $mutationResolver->getErrorType();
        $errorTypeKeys = [ErrorTypes::DESCRIPTIONS => ResponseConstants::ERRORSTRINGS, ErrorTypes::CODES => ResponseConstants::ERRORCODES];
        $errorTypeKey = $errorTypeKeys[$errorType];
        $objectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $mutationResolver->validate($fieldDataAccessorForMutation, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            // @todo Migrate from string to FeedbackItemProvider
            $mutationResponse[$errorTypeKey] = \array_map(function (ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback) {
                return $objectTypeFieldResolutionFeedback->getFeedbackItemResolution()->getMessage();
            }, $objectTypeFieldResolutionFeedbackStore->getErrors());
            if ($this->skipDataloadIfError()) {
                // Bring no results
                $data_properties[DataloadingConstants::SKIPDATALOAD] = \true;
            }
            return $mutationResponse;
        }
        $errorMessage = null;
        $resultID = null;
        try {
            $resultID = $mutationResolver->executeMutation($fieldDataAccessorForMutation, $objectTypeFieldResolutionFeedbackStore);
        } catch (AbstractClientException $e) {
            $errorMessage = $e->getMessage();
            $errorTypeKey = ResponseConstants::ERRORSTRINGS;
        } catch (Exception $e) {
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            if ($moduleConfiguration->logExceptionErrorMessagesAndTraces()) {
                // @todo: Implement for Log
            }
            $errorMessage = $moduleConfiguration->sendExceptionErrorMessages() ? $e->getMessage() : $this->__('Resolving the mutation produced an exception, please contact the admin', 'component-model');
            $errorTypeKey = ResponseConstants::ERRORSTRINGS;
        }
        // @todo Make DRY! This code was copy/pasted from just above
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            // @todo Migrate from string to FeedbackItemProvider
            $mutationResponse[$errorTypeKey] = \array_map(function (ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback) {
                return $objectTypeFieldResolutionFeedback->getFeedbackItemResolution()->getMessage();
            }, $objectTypeFieldResolutionFeedbackStore->getErrors());
            if ($this->skipDataloadIfError()) {
                // Bring no results
                $data_properties[DataloadingConstants::SKIPDATALOAD] = \true;
            }
            return $mutationResponse;
        }
        if ($errorMessage !== null) {
            if ($this->skipDataloadIfError()) {
                // Bring no results
                $data_properties[DataloadingConstants::SKIPDATALOAD] = \true;
            }
            $mutationResponse[$errorTypeKey] = [$errorMessage];
            return $mutationResponse;
        }
        $this->modifyDataProperties($data_properties, $resultID);
        // Save the result for some component to incorporate it into the query args
        App::getMutationResolutionStore()->setResult($this, $resultID);
        $mutationResponse[ResponseConstants::SUCCESS] = \true;
        if ($success_strings = $this->getSuccessStrings($resultID)) {
            $mutationResponse[ResponseConstants::SUCCESSSTRINGS] = $success_strings;
        }
        return $mutationResponse;
    }
    protected function getFieldDataAccessorForMutation() : FieldDataAccessorInterface
    {
        /**
         * Create a runtime field to be executed. It doesn't matter
         * what's the name of the mutation field, so providing
         * a random one suffices.
         */
        $mutationField = new LeafField('someMutation', null, [], [], ASTNodesFactory::getNonSpecificLocation());
        /**
         * Inject the data straight as normalized value (no need to add defaults
         * or coerce values)
         */
        $mutationData = [];
        $this->addMutationDataForFieldDataAccessor($mutationData);
        $fieldDataAccessorForMutation = new FieldDataAccessor($mutationField, $mutationData);
        return $fieldDataAccessorForMutation;
    }
    /**
     * @param array<string,mixed> $data_properties
     * @param string|int $resultID
     */
    protected function modifyDataProperties(&$data_properties, $resultID) : void
    {
    }
}

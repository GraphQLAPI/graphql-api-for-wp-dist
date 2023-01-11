<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostCategoryMutations\Hooks;

use PoPCMSSchema\CustomPostCategoryMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostCategoryMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\SetCategoriesOnCustomPostMutationResolverTrait;
use PoPCMSSchema\CustomPostCategoryMutations\ObjectModels\CategoryDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMutations\Constants\HookNames;
use PoPCMSSchema\CustomPostMutations\Constants\MutationInputProperties as CustomPostMutationsMutationInputProperties;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Hooks\AbstractHookSet;
abstract class AbstractMutationResolverHookSet extends AbstractHookSet
{
    use SetCategoriesOnCustomPostMutationResolverTrait;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface|null
     */
    private $customPostTypeAPI;
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface $customPostTypeAPI
     */
    public final function setCustomPostTypeAPI($customPostTypeAPI) : void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    protected final function getCustomPostTypeAPI() : CustomPostTypeAPIInterface
    {
        /** @var CustomPostTypeAPIInterface */
        return $this->customPostTypeAPI = $this->customPostTypeAPI ?? $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }
    protected function init() : void
    {
        App::addAction(HookNames::VALIDATE_CREATE_OR_UPDATE, \Closure::fromCallable([$this, 'maybeValidateCategories']), 10, 2);
        App::addAction(HookNames::EXECUTE_CREATE_OR_UPDATE, \Closure::fromCallable([$this, 'maybeSetCategories']), 10, 2);
        App::addFilter(HookNames::ERROR_PAYLOAD, \Closure::fromCallable([$this, 'createErrorPayloadFromObjectTypeFieldResolutionFeedback']), 10, 2);
    }
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function maybeValidateCategories($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        if (!$this->canExecuteMutation($fieldDataAccessor)) {
            return;
        }
        $customPostCategoryIDs = $fieldDataAccessor->getValue(MutationInputProperties::CATEGORY_IDS);
        $this->validateCategoriesExist($customPostCategoryIDs, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function canExecuteMutation($fieldDataAccessor) : bool
    {
        if (!$fieldDataAccessor->hasValue(MutationInputProperties::CATEGORY_IDS)) {
            return \false;
        }
        // Only for that specific CPT
        $customPostID = $fieldDataAccessor->getValue(CustomPostMutationsMutationInputProperties::ID);
        if ($this->getCustomPostTypeAPI()->getCustomPostType($customPostID) !== $this->getCustomPostType()) {
            return \false;
        }
        return \true;
    }
    /**
     * @param int|string $customPostID
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    public function maybeSetCategories($customPostID, $fieldDataAccessor) : void
    {
        if (!$this->canExecuteMutation($fieldDataAccessor)) {
            return;
        }
        $customPostCategoryIDs = $fieldDataAccessor->getValue(MutationInputProperties::CATEGORY_IDS);
        $customPostCategoryTypeMutationAPI = $this->getCustomPostCategoryTypeMutationAPI();
        $customPostCategoryTypeMutationAPI->setCategories($customPostID, $customPostCategoryIDs, \false);
    }
    /**
     * @param \PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface $errorPayload
     * @param \PoP\Root\Feedback\FeedbackItemResolution $feedbackItemResolution
     */
    public function createErrorPayloadFromObjectTypeFieldResolutionFeedback($errorPayload, $feedbackItemResolution) : ErrorPayloadInterface
    {
        switch ([$feedbackItemResolution->getFeedbackProviderServiceClass(), $feedbackItemResolution->getCode()]) {
            case [MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E2]:
                return new CategoryDoesNotExistErrorPayload($feedbackItemResolution->getMessage());
            default:
                return $errorPayload;
        }
    }
    protected abstract function getCustomPostType() : string;
    protected abstract function getCustomPostCategoryTypeMutationAPI() : CustomPostCategoryTypeMutationAPIInterface;
}

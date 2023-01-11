<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\Hooks;

use PoPCMSSchema\CustomPostMediaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostMediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolverTrait;
use PoPCMSSchema\CustomPostMediaMutations\ObjectModels\MediaItemDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMutations\Constants\HookNames;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Hooks\AbstractHookSet;
class MutationResolverHookSet extends AbstractHookSet
{
    use SetFeaturedImageOnCustomPostMutationResolverTrait;
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface|null
     */
    private $customPostMediaTypeMutationAPI;
    /**
     * @var \PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface|null
     */
    private $mediaTypeAPI;
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI
     */
    public final function setCustomPostMediaTypeMutationAPI($customPostMediaTypeMutationAPI) : void
    {
        $this->customPostMediaTypeMutationAPI = $customPostMediaTypeMutationAPI;
    }
    protected final function getCustomPostMediaTypeMutationAPI() : CustomPostMediaTypeMutationAPIInterface
    {
        /** @var CustomPostMediaTypeMutationAPIInterface */
        return $this->customPostMediaTypeMutationAPI = $this->customPostMediaTypeMutationAPI ?? $this->instanceManager->getInstance(CustomPostMediaTypeMutationAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface $mediaTypeAPI
     */
    public final function setMediaTypeAPI($mediaTypeAPI) : void
    {
        $this->mediaTypeAPI = $mediaTypeAPI;
    }
    protected final function getMediaTypeAPI() : MediaTypeAPIInterface
    {
        /** @var MediaTypeAPIInterface */
        return $this->mediaTypeAPI = $this->mediaTypeAPI ?? $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
    }
    protected function init() : void
    {
        App::addAction(HookNames::VALIDATE_CREATE_OR_UPDATE, \Closure::fromCallable([$this, 'maybeValidateFeaturedImage']), 10, 2);
        App::addAction(HookNames::EXECUTE_CREATE_OR_UPDATE, \Closure::fromCallable([$this, 'maybeSetOrRemoveFeaturedImage']), 10, 2);
        App::addFilter(HookNames::ERROR_PAYLOAD, \Closure::fromCallable([$this, 'createErrorPayloadFromObjectTypeFieldResolutionFeedback']), 10, 2);
    }
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function maybeValidateFeaturedImage($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        if (!$this->canExecuteMutation($fieldDataAccessor)) {
            return;
        }
        $featuredImageID = $fieldDataAccessor->getValue(MutationInputProperties::FEATUREDIMAGE_ID);
        if ($featuredImageID === null) {
            return;
        }
        $this->validateMediaItemExists($featuredImageID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * Entry "featuredImageID" must either have an ID or `null` to execute
     * the mutation. Only if not provided, then nothing to do.
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function canExecuteMutation($fieldDataAccessor) : bool
    {
        return $fieldDataAccessor->hasValue(MutationInputProperties::FEATUREDIMAGE_ID);
    }
    /**
     * If entry "featuredImageID" has an ID, set it. If it is null, remove it
     * @param int|string $customPostID
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    public function maybeSetOrRemoveFeaturedImage($customPostID, $fieldDataAccessor) : void
    {
        if (!$this->canExecuteMutation($fieldDataAccessor)) {
            return;
        }
        /**
         * If it has an ID, set the featured image
         *
         * @var string|int|null
         */
        $featuredImageID = $fieldDataAccessor->getValue(MutationInputProperties::FEATUREDIMAGE_ID);
        if ($featuredImageID !== null) {
            $this->getCustomPostMediaTypeMutationAPI()->setFeaturedImage($customPostID, $featuredImageID);
            return;
        }
        /**
         * If is `null` => remove the featured image
         */
        $this->getCustomPostMediaTypeMutationAPI()->removeFeaturedImage($customPostID);
    }
    /**
     * @param \PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface $errorPayload
     * @param \PoP\Root\Feedback\FeedbackItemResolution $feedbackItemResolution
     */
    public function createErrorPayloadFromObjectTypeFieldResolutionFeedback($errorPayload, $feedbackItemResolution) : ErrorPayloadInterface
    {
        switch ([$feedbackItemResolution->getFeedbackProviderServiceClass(), $feedbackItemResolution->getCode()]) {
            case [MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E2]:
                return new MediaItemDoesNotExistErrorPayload($feedbackItemResolution->getMessage());
            default:
                return $errorPayload;
        }
    }
}

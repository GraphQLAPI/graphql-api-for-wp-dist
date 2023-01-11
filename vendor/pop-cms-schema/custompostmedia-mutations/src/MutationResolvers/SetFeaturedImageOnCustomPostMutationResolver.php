<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
class SetFeaturedImageOnCustomPostMutationResolver extends \PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\AbstractSetOrRemoveFeaturedImageOnCustomPostMutationResolver
{
    use \PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolverTrait;
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
    /**
     * @throws AbstractException In case of error
     * @return mixed
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function executeMutation($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        $customPostID = $fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID);
        $mediaItemID = $fieldDataAccessor->getValue(MutationInputProperties::MEDIA_ITEM_ID);
        $this->getCustomPostMediaTypeMutationAPI()->setFeaturedImage($customPostID, $mediaItemID);
        return $customPostID;
    }
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        parent::validate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        $mediaItemID = $fieldDataAccessor->getValue(MutationInputProperties::MEDIA_ITEM_ID);
        $this->validateMediaItemExists($mediaItemID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}

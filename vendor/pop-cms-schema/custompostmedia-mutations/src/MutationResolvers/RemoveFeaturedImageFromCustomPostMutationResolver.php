<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Exception\AbstractException;
class RemoveFeaturedImageFromCustomPostMutationResolver extends \PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\AbstractSetOrRemoveFeaturedImageOnCustomPostMutationResolver
{
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface|null
     */
    private $customPostMediaTypeMutationAPI;
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
     * @throws AbstractException In case of error
     * @return mixed
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function executeMutation($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        $customPostID = $fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID);
        $this->getCustomPostMediaTypeMutationAPI()->removeFeaturedImage($customPostID);
        return $customPostID;
    }
}

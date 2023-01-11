<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMediaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
trait SetFeaturedImageOnCustomPostMutationResolverTrait
{
    /**
     * @param string|int|null $mediaItemID
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validateMediaItemExists($mediaItemID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        if ($mediaItemID === null) {
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E1), $fieldDataAccessor->getField()));
            return;
        }
        if (!$this->getMediaTypeAPI()->mediaItemExists($mediaItemID)) {
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E2, [$mediaItemID]), $fieldDataAccessor->getField()));
        }
    }
    protected abstract function getMediaTypeAPI() : MediaTypeAPIInterface;
}

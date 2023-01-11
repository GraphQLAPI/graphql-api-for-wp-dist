<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CustomPostMediaTypeMutationAPIInterface
{
    /**
     * @param int|string $customPostID
     * @param string|int $mediaItemID
     */
    public function setFeaturedImage($customPostID, $mediaItemID) : void;
    /**
     * @param int|string $customPostID
     */
    public function removeFeaturedImage($customPostID) : void;
}

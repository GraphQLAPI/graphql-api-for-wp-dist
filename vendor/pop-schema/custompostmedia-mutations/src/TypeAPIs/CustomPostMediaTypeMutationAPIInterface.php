<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMediaMutations\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CustomPostMediaTypeMutationAPIInterface
{
    /**
     * @param string|int $mediaItemID
     * @param int|string $customPostID
     */
    public function setFeaturedImage($customPostID, $mediaItemID) : void;
    /**
     * @param int|string $customPostID
     */
    public function removeFeaturedImage($customPostID) : void;
}

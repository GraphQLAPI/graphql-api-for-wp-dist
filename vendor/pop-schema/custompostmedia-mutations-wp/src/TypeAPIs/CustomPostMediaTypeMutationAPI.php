<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutationsWP\TypeAPIs;

use PoPSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostMediaTypeMutationAPI implements CustomPostMediaTypeMutationAPIInterface
{
    /**
     * @param int|string $customPostID
     * @param string|int $mediaItemID
     */
    public function setFeaturedImage($customPostID, $mediaItemID): void
    {
        \set_post_thumbnail($customPostID, $mediaItemID);
    }

    /**
     * @param int|string $customPostID
     */
    public function removeFeaturedImage($customPostID): void
    {
        \delete_post_thumbnail($customPostID);
    }
}

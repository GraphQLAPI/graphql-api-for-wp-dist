<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutationsWP\TypeAPIs;

use PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;

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
        \set_post_thumbnail((int)$customPostID, (int)$mediaItemID);
    }

    /**
     * @param int|string $customPostID
     */
    public function removeFeaturedImage($customPostID): void
    {
        \delete_post_thumbnail((int)$customPostID);
    }
}

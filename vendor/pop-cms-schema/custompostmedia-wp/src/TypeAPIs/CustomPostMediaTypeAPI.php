<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaWP\TypeAPIs;

use PoPCMSSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface;
use WP_Post;

use function get_post_thumbnail_id;
use function has_post_thumbnail;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostMediaTypeAPI implements CustomPostMediaTypeAPIInterface
{
    /**
     * @see https://developer.wordpress.org/reference/functions/post_type_supports/
     * @param string $customPostType
     */
    public function doesCustomPostTypeSupportFeaturedImage($customPostType): bool
    {
        return post_type_supports($customPostType, 'thumbnail');
    }

    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function hasCustomPostThumbnail($customPostObjectOrID): bool
    {
        if (is_object($customPostObjectOrID)) {
            /** @var WP_Post */
            $customPost = $customPostObjectOrID;
            $customPostID = $customPost->ID;
        } else {
            $customPostID = (int)$customPostObjectOrID;
        }
        return has_post_thumbnail($customPostID);
    }

    /**
     * @param string|int|object $customPostObjectOrID
     * @return string|int|null
     */
    public function getCustomPostThumbnailID($customPostObjectOrID)
    {
        if (is_object($customPostObjectOrID)) {
            /** @var WP_Post */
            $customPost = $customPostObjectOrID;
            $customPostID = $customPost->ID;
        } else {
            $customPostID = (int)$customPostObjectOrID;
        }
        if ($id = get_post_thumbnail_id($customPostID)) {
            return (int)$id;
        }
        return null;
    }
}

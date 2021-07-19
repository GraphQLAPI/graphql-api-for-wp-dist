<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaWP\TypeAPIs;

use PoPSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface;

use function has_post_thumbnail;
use function get_post_thumbnail_id;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class CustomPostMediaTypeAPI implements CustomPostMediaTypeAPIInterface
{
    /**
     * @param string|int $post_id
     */
    public function hasCustomPostThumbnail($post_id): bool
    {
        return has_post_thumbnail($post_id);
    }

    /**
     * @param string|int $post_id
     * @return string|int|null
     */
    public function getCustomPostThumbnailID($post_id)
    {
        if ($id = get_post_thumbnail_id($post_id)) {
            return (int)$id;
        }
        return null;
    }
}

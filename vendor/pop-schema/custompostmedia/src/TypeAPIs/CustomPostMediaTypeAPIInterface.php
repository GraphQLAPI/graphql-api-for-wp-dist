<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMedia\TypeAPIs;

interface CustomPostMediaTypeAPIInterface
{
    /**
     * @param string|int $post_id
     */
    public function hasCustomPostThumbnail($post_id) : bool;
    /**
     * @param string|int $post_id
     * @return string|int|null
     */
    public function getCustomPostThumbnailID($post_id);
}

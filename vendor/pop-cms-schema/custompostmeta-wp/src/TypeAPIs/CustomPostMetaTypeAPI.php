<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaWP\TypeAPIs;

use PoPCMSSchema\CustomPostMeta\TypeAPIs\AbstractCustomPostMetaTypeAPI;
use WP_Post;

class CustomPostMetaTypeAPI extends AbstractCustomPostMetaTypeAPI
{
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     * @param string|int|object $customPostObjectOrID
     * @return mixed
     * @param string $key
     * @param bool $single
     */
    protected function doGetCustomPostMeta($customPostObjectOrID, $key, $single = false)
    {
        if (is_object($customPostObjectOrID)) {
            /** @var WP_Post */
            $customPost = $customPostObjectOrID;
            $customPostID = $customPost->ID;
        } else {
            $customPostID = $customPostObjectOrID;
        }

        // This function does not differentiate between a stored empty value,
        // and a non-existing key! So if empty, treat it as non-existant and return null
        $value = \get_post_meta((int)$customPostID, $key, $single);
        if (($single && $value === '') || (!$single && $value === [])) {
            return null;
        }
        return $value;
    }
}

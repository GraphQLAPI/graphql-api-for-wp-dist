<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMetaWP\TypeAPIs;

use PoPSchema\CustomPostMeta\TypeAPIs\AbstractCustomPostMetaTypeAPI;

class CustomPostMetaTypeAPI extends AbstractCustomPostMetaTypeAPI
{
    /**
     * @param string|int $customPostID
     * @return mixed
     */
    protected function doGetCustomPostMeta($customPostID, string $key, bool $single = false)
    {
        return \get_post_meta($customPostID, $key, $single);
    }
}

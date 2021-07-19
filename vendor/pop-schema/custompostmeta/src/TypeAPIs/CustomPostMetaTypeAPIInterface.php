<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMeta\TypeAPIs;

interface CustomPostMetaTypeAPIInterface
{
    /**
     * @param string|int $customPostID
     * @return mixed
     */
    public function getCustomPostMeta($customPostID, string $key, bool $single = \false);
}

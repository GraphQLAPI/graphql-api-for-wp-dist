<?php

declare(strict_types=1);

namespace PoPSchema\CommentMetaWP\TypeAPIs;

use PoPSchema\CommentMeta\TypeAPIs\AbstractCommentMetaTypeAPI;

class CommentMetaTypeAPI extends AbstractCommentMetaTypeAPI
{
    /**
     * @param string|int $commentID
     * @return mixed
     */
    public function doGetCommentMeta($commentID, string $key, bool $single = false)
    {
        return \get_comment_meta($commentID, $key, $single);
    }
}

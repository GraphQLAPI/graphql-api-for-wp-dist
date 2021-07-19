<?php

declare (strict_types=1);
namespace PoPSchema\CommentMeta\TypeAPIs;

interface CommentMetaTypeAPIInterface
{
    /**
     * @param string|int $commentID
     * @return mixed
     */
    public function getCommentMeta($commentID, string $key, bool $single = \false);
}

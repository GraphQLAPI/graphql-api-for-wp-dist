<?php

declare (strict_types=1);
namespace PoPSchema\Comments\ConditionalOnComponent\Users\TypeAPIs;

interface CommentTypeAPIInterface
{
    /**
     * @return string|int|null
     * @param object $comment
     */
    public function getCommentUserId($comment);
}

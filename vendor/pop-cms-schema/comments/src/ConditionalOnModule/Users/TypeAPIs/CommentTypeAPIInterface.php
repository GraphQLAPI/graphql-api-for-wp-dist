<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\TypeAPIs;

interface CommentTypeAPIInterface
{
    /**
     * @return string|int|null
     * @param object $comment
     */
    public function getCommentUserID($comment);
}

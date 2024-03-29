<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\TypeAPIs;

use PoPCMSSchema\CommentMutations\Exception\CommentCRUDMutationException;
/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CommentTypeMutationAPIInterface
{
    /**
     * @throws CommentCRUDMutationException In case of error
     * @param array<string,mixed> $comment_data
     * @return string|int
     */
    public function insertComment($comment_data);
}

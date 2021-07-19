<?php

declare (strict_types=1);
namespace PoPSchema\Comments\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CommentTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Comment
     * @param object $object
     */
    public function isInstanceOfCommentType($object) : bool;
    public function getComments(array $query, array $options = []) : array;
    /**
     * @param string|int $comment_id
     * @return object|null
     */
    public function getComment($comment_id);
    /**
     * @param string|int $post_id
     */
    public function getCommentNumber($post_id) : int;
    /**
     * @param string|int $post_id
     */
    public function areCommentsOpen($post_id) : bool;
    /**
     * @param object $comment
     */
    public function getCommentContent($comment) : string;
    /**
     * @param object $comment
     */
    public function getCommentPlainContent($comment) : string;
    /**
     * @return int|string
     * @param object $comment
     */
    public function getCommentPostId($comment);
    /**
     * @param object $comment
     */
    public function isCommentApproved($comment) : bool;
    /**
     * @param object $comment
     */
    public function getCommentType($comment) : string;
    /**
     * @return int|string|null
     * @param object $comment
     */
    public function getCommentParent($comment);
    /**
     * @param object $comment
     */
    public function getCommentDateGmt($comment) : string;
    /**
     * @return string|int
     * @param object $comment
     */
    public function getCommentId($comment);
    /**
     * @param object $comment
     */
    public function getCommentAuthorName($comment) : string;
    /**
     * @param object $comment
     */
    public function getCommentAuthorEmail($comment) : string;
    /**
     * @param object $comment
     */
    public function getCommentAuthorURL($comment) : string;
}

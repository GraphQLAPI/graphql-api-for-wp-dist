<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\TypeAPIs;

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
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getComments($query, $options = []) : array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCommentCount($query, $options = []) : int;
    /**
     * @param string|int $comment_id
     */
    public function getComment($comment_id);
    /**
     * @param string|int $post_id
     */
    public function getCommentNumber($post_id) : int;
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function areCommentsOpen($customPostObjectOrID) : bool;
    /**
     * @param object $comment
     */
    public function getCommentContent($comment) : string;
    /**
     * @param object $comment
     */
    public function getCommentRawContent($comment) : string;
    /**
     * @return int|string
     * @param object $comment
     */
    public function getCommentPostID($comment);
    /**
     * @param object $comment
     */
    public function isCommentApproved($comment) : bool;
    /**
     * @param object $comment
     */
    public function getCommentType($comment) : string;
    /**
     * @param object $comment
     */
    public function getCommentStatus($comment) : string;
    /**
     * @return int|string|null
     * @param object $comment
     */
    public function getCommentParent($comment);
    /**
     * @param object $comment
     * @param bool $gmt
     */
    public function getCommentDate($comment, $gmt = \false) : string;
    /**
     * @return string|int
     * @param object $comment
     */
    public function getCommentID($comment);
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
    public function getCommentAuthorURL($comment) : ?string;
    /**
     * @param string $customPostType
     */
    public function doesCustomPostTypeSupportComments($customPostType) : bool;
}

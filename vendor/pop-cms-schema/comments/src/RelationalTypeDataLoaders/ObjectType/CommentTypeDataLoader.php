<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\Comments\Constants\CommentStatus;
use PoPCMSSchema\Comments\Constants\CommentTypes;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;
class CommentTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    /**
     * @var \PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface|null
     */
    private $commentTypeAPI;
    /**
     * @param \PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface $commentTypeAPI
     */
    public final function setCommentTypeAPI($commentTypeAPI) : void
    {
        $this->commentTypeAPI = $commentTypeAPI;
    }
    protected final function getCommentTypeAPI() : CommentTypeAPIInterface
    {
        /** @var CommentTypeAPIInterface */
        return $this->commentTypeAPI = $this->commentTypeAPI ?? $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
    }
    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs($ids) : array
    {
        return ['include' => $ids, 'type' => [CommentTypes::COMMENT, CommentTypes::TRACKBACK, CommentTypes::PINGBACK], 'status' => [CommentStatus::APPROVE, CommentStatus::HOLD, CommentStatus::SPAM, CommentStatus::TRASH], 'custompost-status' => $this->getAllCustomPostStatuses()];
    }
    /**
     * @return string[]
     */
    protected function getAllCustomPostStatuses() : array
    {
        return [CustomPostStatus::PUBLISH, CustomPostStatus::PENDING, CustomPostStatus::DRAFT, CustomPostStatus::TRASH];
    }
    /**
     * @return mixed[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function executeQuery($query, $options = []) : array
    {
        return $this->getCommentTypeAPI()->getComments($query, $options);
    }
    /**
     * @param array<string,mixed> $query
     * @return array<string|int>
     */
    public function executeQueryIDs($query) : array
    {
        $options = [QueryOptions::RETURN_TYPE => ReturnTypes::IDS];
        return $this->executeQuery($query, $options);
    }
}

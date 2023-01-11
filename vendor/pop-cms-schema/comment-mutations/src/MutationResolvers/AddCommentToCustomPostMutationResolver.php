<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\MutationResolvers;

use PoPCMSSchema\CommentMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CommentMutations\Exception\CommentCRUDMutationException;
use PoPCMSSchema\CommentMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMutations\TypeAPIs\CommentTypeMutationAPIInterface;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP\Root\Feedback\FeedbackItemResolution;
/**
 * Add a comment to a custom post. The user may be logged-in or not
 */
class AddCommentToCustomPostMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;
    /**
     * @var \PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface|null
     */
    private $commentTypeAPI;
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeAPIs\CommentTypeMutationAPIInterface|null
     */
    private $commentTypeMutationAPI;
    /**
     * @var \PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface|null
     */
    private $userTypeAPI;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface|null
     */
    private $customPostTypeAPI;
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
     * @param \PoPCMSSchema\CommentMutations\TypeAPIs\CommentTypeMutationAPIInterface $commentTypeMutationAPI
     */
    public final function setCommentTypeMutationAPI($commentTypeMutationAPI) : void
    {
        $this->commentTypeMutationAPI = $commentTypeMutationAPI;
    }
    protected final function getCommentTypeMutationAPI() : CommentTypeMutationAPIInterface
    {
        /** @var CommentTypeMutationAPIInterface */
        return $this->commentTypeMutationAPI = $this->commentTypeMutationAPI ?? $this->instanceManager->getInstance(CommentTypeMutationAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface $userTypeAPI
     */
    public final function setUserTypeAPI($userTypeAPI) : void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    protected final function getUserTypeAPI() : UserTypeAPIInterface
    {
        /** @var UserTypeAPIInterface */
        return $this->userTypeAPI = $this->userTypeAPI ?? $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface $customPostTypeAPI
     */
    public final function setCustomPostTypeAPI($customPostTypeAPI) : void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    protected final function getCustomPostTypeAPI() : CustomPostTypeAPIInterface
    {
        /** @var CustomPostTypeAPIInterface */
        return $this->customPostTypeAPI = $this->customPostTypeAPI ?? $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        $field = $fieldDataAccessor->getField();
        // Check that the user is logged-in
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->mustUserBeLoggedInToAddComment()) {
            $errorFeedbackItemResolution = $this->validateUserIsLoggedIn();
            if ($errorFeedbackItemResolution !== null) {
                $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback($errorFeedbackItemResolution, $field));
                return;
            }
        } elseif ($moduleConfiguration->requireCommenterNameAndEmail()) {
            // Validate if the commenter's name and email are mandatory
            if (!$fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_NAME)) {
                $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E2), $field));
            }
            if (!$fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_EMAIL)) {
                $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E3), $field));
            }
        }
        // Either provide the customPostID, or retrieve it from the parent comment
        if (!$fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID) && !$fieldDataAccessor->getValue(MutationInputProperties::PARENT_COMMENT_ID)) {
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E4), $field));
        }
        // Make sure the parent comment exists
        // Either provide the customPostID, or retrieve it from the parent comment
        if ($parentCommentID = $fieldDataAccessor->getValue(MutationInputProperties::PARENT_COMMENT_ID)) {
            $parentComment = $this->getCommentTypeAPI()->getComment($parentCommentID);
            if ($parentComment === null) {
                $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E6, [$parentCommentID]), $field));
            }
        }
        // Make sure the custom post exists
        if ($customPostID = $fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID)) {
            if (!$this->getCustomPostTypeAPI()->customPostExists($customPostID)) {
                $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E7, [$customPostID]), $field));
            } else {
                // Validate the corresponding CPT supports comments
                /** @var string */
                $customPostType = $this->getCustomPostTypeAPI()->getCustomPostType($customPostID);
                if (!$this->getCommentTypeAPI()->doesCustomPostTypeSupportComments($customPostType)) {
                    $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E8, [$customPostType]), $field));
                } elseif (!$this->getCommentTypeAPI()->areCommentsOpen($customPostID)) {
                    $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E9, [$customPostID]), $field));
                }
            }
        }
        if (!$fieldDataAccessor->getValue(MutationInputProperties::COMMENT)) {
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E5), $field));
        }
    }
    protected function getUserNotLoggedInError() : FeedbackItemResolution
    {
        return new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E1);
    }
    /**
     * @param string|int $comment_id
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function additionals($comment_id, $fieldDataAccessor) : void
    {
        App::doAction('gd_addcomment', $comment_id, $fieldDataAccessor);
    }
    /**
     * @return array<string,mixed>
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function getCommentData($fieldDataAccessor) : array
    {
        $comment_data = ['authorIP' => App::server('REMOTE_ADDR'), 'agent' => App::server('HTTP_USER_AGENT'), 'content' => $fieldDataAccessor->getValue(MutationInputProperties::COMMENT), 'parent' => $fieldDataAccessor->getValue(MutationInputProperties::PARENT_COMMENT_ID), 'customPostID' => $fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID)];
        /**
         * Override with the user's properties
         */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->mustUserBeLoggedInToAddComment()) {
            $userID = App::getState('current-user-id');
            $comment_data['userID'] = $userID;
            $comment_data['author'] = $this->getUserTypeAPI()->getUserDisplayName($userID);
            $comment_data['authorEmail'] = $this->getUserTypeAPI()->getUserEmail($userID);
            $comment_data['authorURL'] = $this->getUserTypeAPI()->getUserWebsiteURL($userID);
        } else {
            if ($userID = App::getState('current-user-id')) {
                $comment_data['userID'] = $userID;
            }
            $comment_data['author'] = $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_NAME);
            $comment_data['authorEmail'] = $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_EMAIL);
            $comment_data['authorURL'] = $fieldDataAccessor->getValue(MutationInputProperties::AUTHOR_URL);
        }
        // If the parent comment is provided and the custom post is not,
        // then retrieve it from there
        if ($comment_data['parent'] && !$comment_data['customPostID']) {
            /** @var object */
            $parentComment = $this->getCommentTypeAPI()->getComment($comment_data['parent']);
            $comment_data['customPostID'] = $this->getCommentTypeAPI()->getCommentPostID($parentComment);
        }
        return $comment_data;
    }
    /**
     * @throws CommentCRUDMutationException In case of error
     * @param array<string,mixed> $comment_data
     * @return string|int
     */
    protected function insertComment($comment_data)
    {
        return $this->getCommentTypeMutationAPI()->insertComment($comment_data);
    }
    /**
     * @throws AbstractException In case of error
     * @return mixed
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function executeMutation($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        $comment_data = $this->getCommentData($fieldDataAccessor);
        $comment_id = $this->insertComment($comment_data);
        // Allow for additional operations (eg: set Action categories)
        $this->additionals($comment_id, $fieldDataAccessor);
        return $comment_id;
    }
}

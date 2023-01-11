<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ModuleConfiguration;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\TypeAPIs\CommentTypeAPIInterface as UserCommentTypeAPIInterface;
use PoPCMSSchema\Comments\FieldResolvers\ObjectType\CommentObjectTypeFieldResolver as UpstreamCommentObjectTypeFieldResolver;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
/**
 * Override fields from the upstream class, getting the data from the user
 */
class CommentObjectTypeFieldResolver extends UpstreamCommentObjectTypeFieldResolver
{
    /**
     * @var UserCommentTypeAPIInterface|null
     */
    private $userCommentTypeAPI;
    /**
     * @var \PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface|null
     */
    private $userTypeAPI;
    /**
     * @param UserCommentTypeAPIInterface $userCommentTypeAPI
     */
    public final function setUserCommentTypeAPI($userCommentTypeAPI) : void
    {
        $this->userCommentTypeAPI = $userCommentTypeAPI;
    }
    protected final function getUserCommentTypeAPI() : UserCommentTypeAPIInterface
    {
        /** @var UserCommentTypeAPIInterface */
        return $this->userCommentTypeAPI = $this->userCommentTypeAPI ?? $this->instanceManager->getInstance(UserCommentTypeAPIInterface::class);
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
     * Execute before the upstream class
     */
    public function getPriorityToAttachToClasses() : int
    {
        return 20;
    }
    /**
     * Only use it when `mustUserBeLoggedInToAddComment`.
     * Check on runtime (not via container) since this option can be changed in WP.
     */
    public function isServiceEnabled() : bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->mustUserBeLoggedInToAddComment();
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['authorName', 'authorURL', 'authorEmail'];
    }
    /**
     * @return mixed
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        $comment = $object;
        $commentUserID = $this->getUserCommentTypeAPI()->getCommentUserID($comment);
        /**
         * Check there is an author. Otherwise, let the upstream resolve it
         */
        if ($commentUserID === null) {
            return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        }
        switch ($fieldDataAccessor->getFieldName()) {
            case 'authorName':
                return $this->getUserTypeAPI()->getUserDisplayName($commentUserID);
            case 'authorURL':
                return $this->getUserTypeAPI()->getUserWebsiteURL($commentUserID);
            case 'authorEmail':
                return $this->getUserTypeAPI()->getUserEmail($commentUserID);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}

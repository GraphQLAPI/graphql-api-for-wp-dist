<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\Overrides\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\FieldResolvers\ObjectType\AddCommentToCustomPostObjectTypeFieldResolverTrait;
use PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType\CommentObjectTypeFieldResolver as UpstreamCommentObjectTypeFieldResolver;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
class CommentObjectTypeFieldResolver extends UpstreamCommentObjectTypeFieldResolver
{
    use AddCommentToCustomPostObjectTypeFieldResolverTrait;
    /**
     * @var \PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface|null
     */
    private $userTypeAPI;
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
     * Higher priority to override the previous FieldResolver
     */
    public function getPriorityToAttachToClasses() : int
    {
        return parent::getPriorityToAttachToClasses() + 10;
    }
    /**
     * If not provided, set the properties from the logged-in user
     *
     * @param array<string,mixed> $fieldArgs
     * @return array<string,mixed>|null null in case of validation error
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function prepareFieldArgs($fieldArgs, $objectTypeResolver, $field, $objectTypeFieldResolutionFeedbackStore) : ?array
    {
        return $this->prepareAddCommentFieldArgs($fieldArgs);
    }
}

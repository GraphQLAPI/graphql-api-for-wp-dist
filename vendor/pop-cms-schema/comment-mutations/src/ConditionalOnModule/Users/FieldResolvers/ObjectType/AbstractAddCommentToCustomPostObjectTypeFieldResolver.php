<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType\AbstractAddCommentToCustomPostObjectTypeFieldResolver as UpstreamAbstractAddCommentToCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
/**
 * This class is placed under ConditionalOnModule/Users/ but there's
 * no need really, as Users will already exist for Mutations packages.
 * It's done like this just to organize the code better.
 */
abstract class AbstractAddCommentToCustomPostObjectTypeFieldResolver extends UpstreamAbstractAddCommentToCustomPostObjectTypeFieldResolver
{
    use \PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\FieldResolvers\ObjectType\AddCommentToCustomPostObjectTypeFieldResolverTrait;
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

<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoPCMSSchema\UserStateMutations\StaticHelpers\AppStateHelpers;
use PoPCMSSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface;
class LogoutUserMutationResolver extends AbstractMutationResolver
{
    use \PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
    /**
     * @var \PoPCMSSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface|null
     */
    private $userStateTypeMutationAPI;
    /**
     * @param \PoPCMSSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface $userStateTypeMutationAPI
     */
    public final function setUserStateTypeMutationAPI($userStateTypeMutationAPI) : void
    {
        $this->userStateTypeMutationAPI = $userStateTypeMutationAPI;
    }
    protected final function getUserStateTypeMutationAPI() : UserStateTypeMutationAPIInterface
    {
        /** @var UserStateTypeMutationAPIInterface */
        return $this->userStateTypeMutationAPI = $this->userStateTypeMutationAPI ?? $this->instanceManager->getInstance(UserStateTypeMutationAPIInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        $errorFeedbackItemResolution = $this->validateUserIsLoggedIn();
        if ($errorFeedbackItemResolution !== null) {
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback($errorFeedbackItemResolution, $fieldDataAccessor->getField()));
        }
    }
    /**
     * @return mixed
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function executeMutation($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        $user_id = App::getState('current-user-id');
        $this->getUserStateTypeMutationAPI()->logout();
        // Modify the routing-state with the newly logged in user info
        AppStateHelpers::resetCurrentUserInAppState();
        App::doAction('gd:user:loggedout', $user_id);
        return $user_id;
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\MutationResolvers;

use PoPCMSSchema\UserStateMutations\Constants\MutationInputProperties;
use PoPCMSSchema\UserStateMutations\Exception\UserStateMutationException;
use PoPCMSSchema\UserStateMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\UserStateMutations\StaticHelpers\AppStateHelpers;
use PoPCMSSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP\Root\Feedback\FeedbackItemResolution;
class LoginUserByCredentialsMutationResolver extends AbstractMutationResolver
{
    /**
     * @var \PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface|null
     */
    private $userTypeAPI;
    /**
     * @var \PoPCMSSchema\UserStateMutations\TypeAPIs\UserStateTypeMutationAPIInterface|null
     */
    private $userStateTypeMutationAPI;
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
        $usernameOrEmail = $fieldDataAccessor->getValue(MutationInputProperties::USERNAME_OR_EMAIL);
        $pwd = $fieldDataAccessor->getValue(MutationInputProperties::PASSWORD);
        if (!$usernameOrEmail) {
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E2), $fieldDataAccessor->getField()));
        }
        if (!$pwd) {
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E3), $fieldDataAccessor->getField()));
        }
        if (App::getState('is-user-logged-in')) {
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback($this->getUserAlreadyLoggedInError(App::getState('current-user-id')), $fieldDataAccessor->getField()));
        }
    }
    /**
     * @param string|int $user_id
     */
    protected function getUserAlreadyLoggedInError($user_id) : FeedbackItemResolution
    {
        return new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E4);
    }
    /**
     * @throws AbstractException In case of error
     * @return mixed
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function executeMutation($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        // If the user is already logged in, then return the error
        $usernameOrEmail = $fieldDataAccessor->getValue(MutationInputProperties::USERNAME_OR_EMAIL);
        $pwd = $fieldDataAccessor->getValue(MutationInputProperties::PASSWORD);
        // Find out if it was a username or an email that was provided
        $isEmail = \strpos($usernameOrEmail, '@') !== \false;
        if ($isEmail) {
            $email = $usernameOrEmail;
            $user = $this->getUserTypeAPI()->getUserByEmail($email);
            if ($user === null) {
                $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E6, [$email]), $fieldDataAccessor->getField()));
                return null;
            }
            $username = $this->getUserTypeAPI()->getUserLogin($user);
        } else {
            $username = $usernameOrEmail;
        }
        $credentials = array('login' => $username, 'password' => $pwd, 'remember' => \true);
        try {
            $user = $this->getUserStateTypeMutationAPI()->login($credentials);
            // Modify the routing-state with the newly logged in user info
            AppStateHelpers::resetCurrentUserInAppState();
            $userID = $this->getUserTypeAPI()->getUserID($user);
            App::doAction('gd:user:loggedin', $userID);
            return $userID;
        } catch (UserStateMutationException $userStateMutationException) {
            $this->transferErrorFromUserStateMutationExceptionToFieldResolutionFeedbackStore($userStateMutationException, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }
    }
    /**
     * @param \PoPCMSSchema\UserStateMutations\Exception\UserStateMutationException $userStateMutationException
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    protected function transferErrorFromUserStateMutationExceptionToFieldResolutionFeedbackStore($userStateMutationException, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        $userLogin = '';
        if ($errorData = $userStateMutationException->getData()) {
            $userLogin = $errorData->userLogin ?? '';
        }
        switch ($userStateMutationException->getErrorCode()) {
            case 'invalid_username':
                $errorFieldResolutionFeedback = new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E5, [$userLogin]);
                break;
            case 'invalid_email':
                $errorFieldResolutionFeedback = new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E6, [$userLogin]);
                break;
            case 'incorrect_password':
                $errorFieldResolutionFeedback = new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E7);
                break;
            default:
                $errorFieldResolutionFeedback = null;
                break;
        }
        $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback($errorFieldResolutionFeedback !== null ? $errorFieldResolutionFeedback : new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E8, [$userStateMutationException->getErrorCode() ?? 'undefined error code', $userStateMutationException->getMessage()]), $fieldDataAccessor->getField()));
    }
}

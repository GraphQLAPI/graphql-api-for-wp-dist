<?php

declare (strict_types=1);
namespace PoPSchema\UserStateMutations\MutationResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\ErrorHandling\Error;
use PoPSchema\UserStateMutations\Facades\UserStateTypeAPIFacade;
use PoPSchema\UserState\State\ApplicationStateUtils;
class LoginMutationResolver extends \PoP\ComponentModel\MutationResolvers\AbstractMutationResolver
{
    public function validateErrors(array $form_data) : ?array
    {
        $errors = [];
        $username_or_email = $form_data[\PoPSchema\UserStateMutations\MutationResolvers\MutationInputProperties::USERNAME_OR_EMAIL];
        $pwd = $form_data[\PoPSchema\UserStateMutations\MutationResolvers\MutationInputProperties::PASSWORD];
        if (!$username_or_email) {
            $errors[] = \PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('Please supply your username or email', 'user-state-mutations');
        }
        if (!$pwd) {
            $errors[] = \PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('Please supply your password', 'user-state-mutations');
        }
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        if ($vars['global-userstate']['is-user-logged-in']) {
            $errors[] = $this->getUserAlreadyLoggedInErrorMessage($user_id);
        }
        return $errors;
    }
    /**
     * @param mixed $user_id Maybe int, maybe string
     */
    protected function getUserAlreadyLoggedInErrorMessage($user_id) : string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('You are already logged in', 'user-state-mutations');
    }
    /**
     * @return mixed
     */
    public function execute(array $form_data)
    {
        // If the user is already logged in, then return the error
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $cmsusersresolver = \PoPSchema\Users\ObjectPropertyResolverFactory::getInstance();
        $userStateTypeAPI = \PoPSchema\UserStateMutations\Facades\UserStateTypeAPIFacade::getInstance();
        $username_or_email = $form_data[\PoPSchema\UserStateMutations\MutationResolvers\MutationInputProperties::USERNAME_OR_EMAIL];
        $pwd = $form_data[\PoPSchema\UserStateMutations\MutationResolvers\MutationInputProperties::PASSWORD];
        // Find out if it was a username or an email that was provided
        $is_email = \strpos($username_or_email, '@');
        if ($is_email) {
            $user = $cmsusersapi->getUserByEmail($username_or_email);
            if (!$user) {
                return new \PoP\ComponentModel\ErrorHandling\Error('no-user', \PoP\Translation\Facades\TranslationAPIFacade::getInstance()->__('There is no user registered with that email address.'));
            }
            $username = $cmsusersresolver->getUserLogin($user);
        } else {
            $username = $username_or_email;
        }
        $credentials = array('login' => $username, 'password' => $pwd, 'remember' => \true);
        $loginResult = $userStateTypeAPI->login($credentials);
        if (\PoP\ComponentModel\Misc\GeneralUtils::isError($loginResult)) {
            return $loginResult;
        }
        $user = $loginResult;
        // Modify the routing-state with the newly logged in user info
        \PoPSchema\UserState\State\ApplicationStateUtils::setUserStateVars(\PoP\ComponentModel\State\ApplicationState::$vars);
        $userID = $cmsusersresolver->getUserId($user);
        \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->doAction('gd:user:loggedin', $userID);
        return $userID;
    }
}

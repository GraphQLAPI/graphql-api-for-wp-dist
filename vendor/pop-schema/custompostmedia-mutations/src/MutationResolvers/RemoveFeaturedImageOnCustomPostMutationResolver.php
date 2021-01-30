<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMediaMutations\MutationResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPSchema\CustomPostMediaMutations\Facades\CustomPostMediaTypeAPIFacade;
use PoPSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
class RemoveFeaturedImageOnCustomPostMutationResolver extends \PoP\ComponentModel\MutationResolvers\AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;
    /**
     * @return mixed
     */
    public function execute(array $form_data)
    {
        $customPostID = $form_data[\PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties::CUSTOMPOST_ID];
        $customPostMediaTypeAPI = \PoPSchema\CustomPostMediaMutations\Facades\CustomPostMediaTypeAPIFacade::getInstance();
        $customPostMediaTypeAPI->removeFeaturedImage($customPostID);
        return $customPostID;
    }
    public function validateErrors(array $form_data) : ?array
    {
        $errors = [];
        // Check that the user is logged-in
        $this->validateUserIsLoggedIn($errors);
        if ($errors) {
            return $errors;
        }
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        if (!$form_data[\PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties::CUSTOMPOST_ID]) {
            $errors[] = $translationAPI->__('The custom post ID is missing.', 'custompostmedia-mutations');
        }
        return $errors;
    }
}

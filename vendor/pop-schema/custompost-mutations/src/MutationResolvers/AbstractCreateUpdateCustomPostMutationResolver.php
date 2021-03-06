<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMutations\MutationResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\CustomPosts\Enums\CustomPostStatusEnum;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoPSchema\UserRoles\Facades\UserRoleTypeDataResolverFacade;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
use PoPSchema\CustomPostMutations\Facades\CustomPostTypeAPIFacade as MutationCustomPostTypeAPIFacade;
use PoPSchema\CustomPostMutations\LooseContracts\LooseContractSet;
use PoPSchema\CustomPosts\Types\Status;
abstract class AbstractCreateUpdateCustomPostMutationResolver extends \PoP\ComponentModel\MutationResolvers\AbstractMutationResolver implements \PoPSchema\CustomPostMutations\MutationResolvers\CustomPostMutationResolverInterface
{
    use ValidateUserLoggedInMutationResolverTrait;
    public const HOOK_EXECUTE_CREATE_OR_UPDATE = __CLASS__ . ':execute-create-or-update';
    public const HOOK_EXECUTE_CREATE = __CLASS__ . ':execute-create';
    public const HOOK_EXECUTE_UPDATE = __CLASS__ . ':execute-update';
    public const HOOK_VALIDATE_CONTENT = __CLASS__ . ':validate-content';
    // @TODO: Migrate when package "Categories" is completed
    // protected function getCategoryTaxonomy(): ?string
    // {
    //     return null;
    // }
    protected function validateCreateErrors(array $form_data) : ?array
    {
        $errors = [];
        // If there are errors here, don't keep validating others
        $this->validateCreateUpdateErrors($errors, $form_data);
        if ($errors) {
            return $errors;
        }
        // If already exists any of these errors above, return errors
        $this->validateCreate($errors, $form_data);
        if ($errors) {
            return $errors;
        }
        $this->validateContent($errors, $form_data);
        $this->validateCreateContent($errors, $form_data);
        return $errors;
    }
    protected function validateUpdateErrors(array $form_data) : ?array
    {
        $errors = [];
        // If there are errors here, don't keep validating others
        $this->validateCreateUpdateErrors($errors, $form_data);
        if ($errors) {
            return $errors;
        }
        // If already exists any of these errors above, return errors
        $this->validateUpdate($errors, $form_data);
        if ($errors) {
            return $errors;
        }
        $this->validateContent($errors, $form_data);
        $this->validateUpdateContent($errors, $form_data);
        return $errors;
    }
    protected function validateCreateUpdateErrors(array &$errors, array $form_data) : void
    {
        // Check that the user is logged-in
        $this->validateUserIsLoggedIn($errors);
        if ($errors) {
            return;
        }
        $nameResolver = \PoP\LooseContracts\Facades\NameResolverFacade::getInstance();
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        // Validate user permission
        $userRoleTypeDataResolver = \PoPSchema\UserRoles\Facades\UserRoleTypeDataResolverFacade::getInstance();
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        $userID = $vars['global-userstate']['current-user-id'];
        $editCustomPostsCapability = $nameResolver->getName(\PoPSchema\CustomPostMutations\LooseContracts\LooseContractSet::NAME_EDIT_CUSTOMPOSTS_CAPABILITY);
        if (!$userRoleTypeDataResolver->userCan($userID, $editCustomPostsCapability)) {
            $errors[] = $translationAPI->__('Your user doesn\'t have permission for editing custom posts.', 'custompost-mutations');
            return;
        }
        // Check if the user can publish custom posts
        if (isset($form_data[\PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::STATUS]) && $form_data[\PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::STATUS] == \PoPSchema\CustomPosts\Types\Status::PUBLISHED) {
            $publishCustomPostsCapability = $nameResolver->getName(\PoPSchema\CustomPostMutations\LooseContracts\LooseContractSet::NAME_PUBLISH_CUSTOMPOSTS_CAPABILITY);
            if (!$userRoleTypeDataResolver->userCan($userID, $publishCustomPostsCapability)) {
                $errors[] = $translationAPI->__('Your user doesn\'t have permission for publishing custom posts.', 'custompost-mutations');
                return;
            }
        }
    }
    protected function getUserNotLoggedInErrorMessage() : string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('You must be logged in to create or update custom posts', 'custompost-mutations');
    }
    protected function validateContent(array &$errors, array $form_data) : void
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        // Validate that the status is valid
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        /**
         * @var CustomPostStatusEnum
         */
        $customPostStatusEnum = $instanceManager->getInstance(\PoPSchema\CustomPosts\Enums\CustomPostStatusEnum::class);
        if (isset($form_data[\PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::STATUS])) {
            $status = $form_data[\PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::STATUS];
            if (!\in_array($status, $customPostStatusEnum->getValues())) {
                $errors[] = \sprintf($translationAPI->__('Status \'%s\' is not supported', 'custompost-mutations'), $status);
            }
        }
        // Allow plugins to add validation for their fields
        $hooksAPI = \PoP\Hooks\Facades\HooksAPIFacade::getInstance();
        $hooksAPI->doAction(self::HOOK_VALIDATE_CONTENT, array(&$errors), $form_data);
    }
    protected function validateCreateContent(array &$errors, array $form_data) : void
    {
    }
    protected function validateUpdateContent(array &$errors, array $form_data) : void
    {
    }
    protected function validateCreate(array &$errors, array $form_data) : void
    {
    }
    protected function validateUpdate(array &$errors, array $form_data) : void
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $customPostID = $form_data[\PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::ID] ?? null;
        if (!$customPostID) {
            $errors[] = $translationAPI->__('The ID is missing', 'custompost-mutations');
            return;
        }
        $customPostTypeAPI = \PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade::getInstance();
        $post = $customPostTypeAPI->getCustomPost($customPostID);
        if (!$post) {
            $errors[] = \sprintf($translationAPI->__('There is no entity with ID \'%s\'', 'custompost-mutations'), $customPostID);
            return;
        }
        // Check that the user has access to the edited custom post
        $mutationCustomPostTypeAPI = \PoPSchema\CustomPostMutations\Facades\CustomPostTypeAPIFacade::getInstance();
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        $userID = $vars['global-userstate']['current-user-id'];
        if (!$mutationCustomPostTypeAPI->canUserEditCustomPost($userID, $customPostID)) {
            $errors[] = \sprintf($translationAPI->__('You don\'t have permission to edit custom post with ID \'%s\'', 'custompost-mutations'), $customPostID);
            return;
        }
    }
    /**
     * @param mixed $customPostID
     */
    protected function additionals($customPostID, array $form_data) : void
    {
    }
    /**
     * @param mixed $customPostID
     */
    protected function updateAdditionals($customPostID, array $form_data, array $log) : void
    {
    }
    /**
     * @param mixed $customPostID
     */
    protected function createAdditionals($customPostID, array $form_data) : void
    {
    }
    // protected function addCustomPostType(&$post_data)
    // {
    //     $post_data['custompost-type'] = $this->getCustomPostType();
    // }
    protected function addCreateUpdateCustomPostData(array &$post_data, array $form_data) : void
    {
        if (isset($form_data[\PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::CONTENT])) {
            $post_data['content'] = $form_data[\PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::CONTENT];
        }
        if (isset($form_data[\PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::TITLE])) {
            $post_data['title'] = $form_data[\PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::TITLE];
        }
        if (isset($form_data[\PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::STATUS])) {
            $post_data['status'] = $form_data[\PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::STATUS];
        }
    }
    protected function getUpdateCustomPostData(array $form_data) : array
    {
        $post_data = array('id' => $form_data[\PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::ID] ?? null);
        $this->addCreateUpdateCustomPostData($post_data, $form_data);
        return $post_data;
    }
    protected function getCreateCustomPostData(array $form_data) : array
    {
        $post_data = ['custompost-type' => $this->getCustomPostType()];
        $this->addCreateUpdateCustomPostData($post_data, $form_data);
        // $this->addCustomPostType($post_data);
        return $post_data;
    }
    /**
     * @param array<string, mixed> $data
     * @return mixed the ID of the updated custom post
     */
    protected function executeUpdateCustomPost(array $data)
    {
        $customPostTypeAPI = \PoPSchema\CustomPostMutations\Facades\CustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI->updateCustomPost($data);
    }
    // @TODO: Migrate when package "Categories" is completed
    // protected function getCategories(array $form_data): ?array
    // {
    //     return $form_data[MutationInputProperties::CATEGORIES];
    // }
    /**
     * @param mixed $customPostID
     */
    protected function createUpdateCustomPost(array $form_data, $customPostID) : void
    {
        // @TODO: Migrate when package "Categories" is completed
        // // Set categories for any taxonomy (not only for "category")
        // if ($cats = $this->getCategories($form_data)) {
        //     $taxonomyapi = \PoPSchema\Taxonomies\FunctionAPIFactory::getInstance();
        //     $taxonomy = $this->getCategoryTaxonomy();
        //     $taxonomyapi->setPostTerms($customPostID, $cats, $taxonomy);
        // }
    }
    /**
     * @param mixed $customPostID
     */
    protected function getUpdateCustomPostDataLog($customPostID, array $form_data) : array
    {
        $customPostTypeAPI = \PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade::getInstance();
        $log = array('previous-status' => $customPostTypeAPI->getStatus($customPostID));
        return $log;
    }
    /**
     * @return mixed The ID of the updated entity, or an Error
     */
    protected function update(array $form_data)
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $post_data = $this->getUpdateCustomPostData($form_data);
        $customPostID = $post_data['id'];
        // Create the operation log, to see what changed. Needed for
        // - Send email only when post published
        // - Add user notification of post being referenced, only when the reference is new (otherwise it will add the notification each time the user updates the post)
        $log = $this->getUpdateCustomPostDataLog($customPostID, $form_data);
        $result = $this->executeUpdateCustomPost($post_data);
        if ($result === 0) {
            return new \PoP\ComponentModel\ErrorHandling\Error('update-error', $translationAPI->__('Oops, there was a problem... this is embarrassing, huh?', 'custompost-mutations'));
        }
        $this->createUpdateCustomPost($form_data, $customPostID);
        // Allow for additional operations (eg: set Action categories)
        $this->additionals($customPostID, $form_data);
        $this->updateAdditionals($customPostID, $form_data, $log);
        // Inject Share profiles here
        $hooksAPI = \PoP\Hooks\Facades\HooksAPIFacade::getInstance();
        $hooksAPI->doAction(self::HOOK_EXECUTE_CREATE_OR_UPDATE, $customPostID, $form_data);
        $hooksAPI->doAction(self::HOOK_EXECUTE_UPDATE, $customPostID, $log, $form_data);
        return $customPostID;
    }
    /**
     * @param array<string, mixed> $data
     * @return mixed the ID of the created custom post
     */
    protected function executeCreateCustomPost(array $data)
    {
        $customPostTypeAPI = \PoPSchema\CustomPostMutations\Facades\CustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI->createCustomPost($data);
    }
    /**
     * @return mixed The ID of the created entity, or an Error
     */
    protected function create(array $form_data)
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $post_data = $this->getCreateCustomPostData($form_data);
        $customPostID = $this->executeCreateCustomPost($post_data);
        if ($customPostID == 0) {
            return new \PoP\ComponentModel\ErrorHandling\Error('create-error', $translationAPI->__('Oops, there was a problem... this is embarrassing, huh?', 'custompost-mutations'));
        }
        $this->createUpdateCustomPost($form_data, $customPostID);
        // Allow for additional operations (eg: set Action categories)
        $this->additionals($customPostID, $form_data);
        $this->createAdditionals($customPostID, $form_data);
        // Inject Share profiles here
        $hooksAPI = \PoP\Hooks\Facades\HooksAPIFacade::getInstance();
        $hooksAPI->doAction(self::HOOK_EXECUTE_CREATE_OR_UPDATE, $customPostID, $form_data);
        $hooksAPI->doAction(self::HOOK_EXECUTE_CREATE, $customPostID, $form_data);
        return $customPostID;
    }
}

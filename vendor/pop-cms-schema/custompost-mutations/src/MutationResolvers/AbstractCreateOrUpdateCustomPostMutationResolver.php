<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\Constants\HookNames;
use PoPCMSSchema\CustomPostMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMutations\Exception\CustomPostCRUDMutationException;
use PoPCMSSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;
abstract class AbstractCreateOrUpdateCustomPostMutationResolver extends AbstractMutationResolver implements \PoPCMSSchema\CustomPostMutations\MutationResolvers\CustomPostMutationResolverInterface
{
    use \PoPCMSSchema\CustomPostMutations\MutationResolvers\CreateOrUpdateCustomPostMutationResolverTrait;
    /**
     * @var \PoP\LooseContracts\NameResolverInterface|null
     */
    private $nameResolver;
    /**
     * @var \PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface|null
     */
    private $userRoleTypeAPI;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface|null
     */
    private $customPostTypeAPI;
    /**
     * @var \PoPCMSSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface|null
     */
    private $customPostTypeMutationAPI;
    /**
     * @param \PoP\LooseContracts\NameResolverInterface $nameResolver
     */
    public final function setNameResolver($nameResolver) : void
    {
        $this->nameResolver = $nameResolver;
    }
    protected final function getNameResolver() : NameResolverInterface
    {
        /** @var NameResolverInterface */
        return $this->nameResolver = $this->nameResolver ?? $this->instanceManager->getInstance(NameResolverInterface::class);
    }
    /**
     * @param \PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface $userRoleTypeAPI
     */
    public final function setUserRoleTypeAPI($userRoleTypeAPI) : void
    {
        $this->userRoleTypeAPI = $userRoleTypeAPI;
    }
    protected final function getUserRoleTypeAPI() : UserRoleTypeAPIInterface
    {
        /** @var UserRoleTypeAPIInterface */
        return $this->userRoleTypeAPI = $this->userRoleTypeAPI ?? $this->instanceManager->getInstance(UserRoleTypeAPIInterface::class);
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
     * @param \PoPCMSSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface $customPostTypeMutationAPI
     */
    public final function setCustomPostTypeMutationAPI($customPostTypeMutationAPI) : void
    {
        $this->customPostTypeMutationAPI = $customPostTypeMutationAPI;
    }
    protected final function getCustomPostTypeMutationAPI() : CustomPostTypeMutationAPIInterface
    {
        /** @var CustomPostTypeMutationAPIInterface */
        return $this->customPostTypeMutationAPI = $this->customPostTypeMutationAPI ?? $this->instanceManager->getInstance(CustomPostTypeMutationAPIInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    protected function validateCreateErrors($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        $this->validateCreateUpdateErrors($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return;
        }
        $this->validateCreate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    protected function validateUpdateErrors($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        // If there are errors here, don't keep validating others
        $this->validateCreateUpdateErrors($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return;
        }
        $this->validateUpdate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    protected function validateCreateUpdateErrors($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        // Allow components (eg: CustomPostCategoryMutations) to inject their own validations
        App::doAction(HookNames::VALIDATE_CREATE_OR_UPDATE, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();
        $this->validateIsUserLoggedIn($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }
        $this->validateCanLoggedInUserEditCustomPosts($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        // Check if the user can publish custom posts
        if ($fieldDataAccessor->getValue(MutationInputProperties::STATUS) === CustomPostStatus::PUBLISH) {
            $this->validateCanLoggedInUserPublishCustomPosts($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        }
    }
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    protected function validateCreate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        // Allow components (eg: CustomPostCategoryMutations) to inject their own validations
        App::doAction(HookNames::VALIDATE_CREATE, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    protected function validateUpdate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        // Allow components (eg: CustomPostCategoryMutations) to inject their own validations
        App::doAction(HookNames::VALIDATE_UPDATE, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();
        $customPostID = $fieldDataAccessor->getValue(MutationInputProperties::ID);
        $this->validateCustomPostExists($customPostID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }
        $this->validateCanLoggedInUserEditCustomPost($customPostID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * @param int|string $customPostID
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function additionals($customPostID, $fieldDataAccessor) : void
    {
    }
    /**
     * @param array<string,mixed> $log
     * @param int|string $customPostID
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function updateAdditionals($customPostID, $fieldDataAccessor, $log) : void
    {
    }
    /**
     * @param int|string $customPostID
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function createAdditionals($customPostID, $fieldDataAccessor) : void
    {
    }
    // protected function addCustomPostType(&$post_data)
    // {
    //     $post_data['custompost-type'] = $this->getCustomPostType();
    // }
    /**
     * @param array<string,mixed> $post_data
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function addCreateOrUpdateCustomPostData(&$post_data, $fieldDataAccessor) : void
    {
        if ($fieldDataAccessor->hasValue(MutationInputProperties::CONTENT)) {
            $post_data['content'] = $fieldDataAccessor->getValue(MutationInputProperties::CONTENT);
        }
        if ($fieldDataAccessor->hasValue(MutationInputProperties::TITLE)) {
            $post_data['title'] = $fieldDataAccessor->getValue(MutationInputProperties::TITLE);
        }
        if ($fieldDataAccessor->hasValue(MutationInputProperties::STATUS)) {
            $post_data['status'] = $fieldDataAccessor->getValue(MutationInputProperties::STATUS);
        }
    }
    /**
     * @return array<string,mixed>
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function getUpdateCustomPostData($fieldDataAccessor) : array
    {
        $post_data = array('id' => $fieldDataAccessor->getValue(MutationInputProperties::ID));
        $this->addCreateOrUpdateCustomPostData($post_data, $fieldDataAccessor);
        return $post_data;
    }
    /**
     * @return array<string,mixed>
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function getCreateCustomPostData($fieldDataAccessor) : array
    {
        $post_data = ['custompost-type' => $this->getCustomPostType()];
        $this->addCreateOrUpdateCustomPostData($post_data, $fieldDataAccessor);
        return $post_data;
    }
    /**
     * @param array<string,mixed> $post_data
     * @return string|int the ID of the updated custom post
     * @throws CustomPostCRUDMutationException If there was an error (eg: Custom Post does not exist)
     */
    protected function executeUpdateCustomPost($post_data)
    {
        return $this->getCustomPostTypeMutationAPI()->updateCustomPost($post_data);
    }
    /**
     * @param int|string $customPostID
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function createUpdateCustomPost($fieldDataAccessor, $customPostID) : void
    {
    }
    /**
     * @return array<string,string>|null[]
     * @param int|string $customPostID
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function getUpdateCustomPostDataLog($customPostID, $fieldDataAccessor) : array
    {
        return ['previous-status' => $this->getCustomPostTypeAPI()->getStatus($customPostID)];
    }
    /**
     * @return string|int The ID of the updated entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: Custom Post does not exist)
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    protected function update($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        $post_data = $this->getUpdateCustomPostData($fieldDataAccessor);
        $customPostID = $post_data['id'];
        // Create the operation log, to see what changed. Needed for
        // - Send email only when post published
        // - Add user notification of post being referenced, only when the reference is new (otherwise it will add the notification each time the user updates the post)
        $log = $this->getUpdateCustomPostDataLog($customPostID, $fieldDataAccessor);
        $customPostID = $this->executeUpdateCustomPost($post_data);
        $this->createUpdateCustomPost($fieldDataAccessor, $customPostID);
        // Allow for additional operations (eg: set Action categories)
        $this->additionals($customPostID, $fieldDataAccessor);
        $this->updateAdditionals($customPostID, $fieldDataAccessor, $log);
        // Inject Share profiles here
        App::doAction(HookNames::EXECUTE_CREATE_OR_UPDATE, $customPostID, $fieldDataAccessor);
        App::doAction(HookNames::EXECUTE_UPDATE, $customPostID, $log, $fieldDataAccessor);
        return $customPostID;
    }
    /**
     * @param array<string,mixed> $post_data
     * @return string|int the ID of the created custom post
     * @throws CustomPostCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     */
    protected function executeCreateCustomPost($post_data)
    {
        return $this->getCustomPostTypeMutationAPI()->createCustomPost($post_data);
    }
    /**
     * @return string|int The ID of the created entity
     * @throws CustomPostCRUDMutationException If there was an error (eg: some Custom Post creation validation failed)
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function create($fieldDataAccessor)
    {
        $post_data = $this->getCreateCustomPostData($fieldDataAccessor);
        $customPostID = $this->executeCreateCustomPost($post_data);
        $this->createUpdateCustomPost($fieldDataAccessor, $customPostID);
        // Allow for additional operations (eg: set Action categories)
        $this->additionals($customPostID, $fieldDataAccessor);
        $this->createAdditionals($customPostID, $fieldDataAccessor);
        // Inject Share profiles here
        App::doAction(HookNames::EXECUTE_CREATE_OR_UPDATE, $customPostID, $fieldDataAccessor);
        App::doAction(HookNames::EXECUTE_CREATE, $customPostID, $fieldDataAccessor);
        return $customPostID;
    }
}

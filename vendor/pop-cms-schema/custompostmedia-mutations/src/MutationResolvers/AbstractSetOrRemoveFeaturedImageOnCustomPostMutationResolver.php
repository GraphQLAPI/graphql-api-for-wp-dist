<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMediaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\CreateOrUpdateCustomPostMutationResolverTrait;
use PoPCMSSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
abstract class AbstractSetOrRemoveFeaturedImageOnCustomPostMutationResolver extends AbstractMutationResolver
{
    use CreateOrUpdateCustomPostMutationResolverTrait;
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
     * @var \PoPCMSSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface|null
     */
    private $customPostMediaTypeAPI;
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
     * @param \PoPCMSSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface $customPostMediaTypeAPI
     */
    public final function setCustomPostMediaTypeAPI($customPostMediaTypeAPI) : void
    {
        $this->customPostMediaTypeAPI = $customPostMediaTypeAPI;
    }
    protected final function getCustomPostMediaTypeAPI() : CustomPostMediaTypeAPIInterface
    {
        /** @var CustomPostMediaTypeAPIInterface */
        return $this->customPostMediaTypeAPI = $this->customPostMediaTypeAPI ?? $this->instanceManager->getInstance(CustomPostMediaTypeAPIInterface::class);
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
    protected function getUserNotLoggedInError() : FeedbackItemResolution
    {
        return new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E3);
    }
    /**
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();
        $this->validateIsUserLoggedIn($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        $customPostID = $fieldDataAccessor->getValue(MutationInputProperties::CUSTOMPOST_ID);
        $this->validateCustomPostExists($customPostID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }
        $this->validateDoesCustomPostTypeSupportFeaturedImage($customPostID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        $this->validateCanLoggedInUserEditCustomPosts($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }
        $this->validateCanLoggedInUserEditCustomPost($customPostID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * @param string|int $customPostID
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    protected function validateDoesCustomPostTypeSupportFeaturedImage($customPostID, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        /** @var string */
        $customPostType = $this->getCustomPostTypeAPI()->getCustomPostType($customPostID);
        if (!$this->getCustomPostMediaTypeAPI()->doesCustomPostTypeSupportFeaturedImage($customPostType)) {
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(MutationErrorFeedbackItemProvider::class, MutationErrorFeedbackItemProvider::E4, [$customPostType]), $fieldDataAccessor->getField()));
        }
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
class UserObjectTypeFieldResolver extends AbstractWithMetaObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface|null
     */
    private $userMetaTypeAPI;
    /**
     * @param \PoPCMSSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface $userMetaTypeAPI
     */
    public final function setUserMetaTypeAPI($userMetaTypeAPI) : void
    {
        $this->userMetaTypeAPI = $userMetaTypeAPI;
    }
    protected final function getUserMetaTypeAPI() : UserMetaTypeAPIInterface
    {
        /** @var UserMetaTypeAPIInterface */
        return $this->userMetaTypeAPI = $this->userMetaTypeAPI ?? $this->instanceManager->getInstance(UserMetaTypeAPIInterface::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [UserObjectTypeResolver::class];
    }
    protected function getMetaTypeAPI() : MetaTypeAPIInterface
    {
        return $this->getUserMetaTypeAPI();
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
        $user = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'metaValue':
            case 'metaValues':
                return $this->getUserMetaTypeAPI()->getUserMeta($user, $fieldDataAccessor->getValue('key'), $fieldDataAccessor->getFieldName() === 'metaValue');
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}

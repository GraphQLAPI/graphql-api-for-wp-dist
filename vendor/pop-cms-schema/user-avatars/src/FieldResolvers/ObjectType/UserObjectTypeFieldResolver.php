<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserAvatars\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Root\App;
use PoPCMSSchema\UserAvatars\Module;
use PoPCMSSchema\UserAvatars\ModuleConfiguration;
use PoPCMSSchema\UserAvatars\ObjectModels\UserAvatar;
use PoPCMSSchema\UserAvatars\RuntimeRegistries\UserAvatarRuntimeRegistryInterface;
use PoPCMSSchema\UserAvatars\TypeAPIs\UserAvatarTypeAPIInterface;
use PoPCMSSchema\UserAvatars\TypeResolvers\ObjectType\UserAvatarObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
class UserObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\UserAvatars\TypeAPIs\UserAvatarTypeAPIInterface|null
     */
    private $userAvatarTypeAPI;
    /**
     * @var \PoPCMSSchema\UserAvatars\RuntimeRegistries\UserAvatarRuntimeRegistryInterface|null
     */
    private $userAvatarRuntimeRegistry;
    /**
     * @var \PoPCMSSchema\UserAvatars\TypeResolvers\ObjectType\UserAvatarObjectTypeResolver|null
     */
    private $userAvatarObjectTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @param \PoPCMSSchema\UserAvatars\TypeAPIs\UserAvatarTypeAPIInterface $userAvatarTypeAPI
     */
    public final function setUserAvatarTypeAPI($userAvatarTypeAPI) : void
    {
        $this->userAvatarTypeAPI = $userAvatarTypeAPI;
    }
    protected final function getUserAvatarTypeAPI() : UserAvatarTypeAPIInterface
    {
        /** @var UserAvatarTypeAPIInterface */
        return $this->userAvatarTypeAPI = $this->userAvatarTypeAPI ?? $this->instanceManager->getInstance(UserAvatarTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\UserAvatars\RuntimeRegistries\UserAvatarRuntimeRegistryInterface $userAvatarRuntimeRegistry
     */
    public final function setUserAvatarRuntimeRegistry($userAvatarRuntimeRegistry) : void
    {
        $this->userAvatarRuntimeRegistry = $userAvatarRuntimeRegistry;
    }
    protected final function getUserAvatarRuntimeRegistry() : UserAvatarRuntimeRegistryInterface
    {
        /** @var UserAvatarRuntimeRegistryInterface */
        return $this->userAvatarRuntimeRegistry = $this->userAvatarRuntimeRegistry ?? $this->instanceManager->getInstance(UserAvatarRuntimeRegistryInterface::class);
    }
    /**
     * @param \PoPCMSSchema\UserAvatars\TypeResolvers\ObjectType\UserAvatarObjectTypeResolver $userAvatarObjectTypeResolver
     */
    public final function setUserAvatarObjectTypeResolver($userAvatarObjectTypeResolver) : void
    {
        $this->userAvatarObjectTypeResolver = $userAvatarObjectTypeResolver;
    }
    protected final function getUserAvatarObjectTypeResolver() : UserAvatarObjectTypeResolver
    {
        /** @var UserAvatarObjectTypeResolver */
        return $this->userAvatarObjectTypeResolver = $this->userAvatarObjectTypeResolver ?? $this->instanceManager->getInstance(UserAvatarObjectTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver $intScalarTypeResolver
     */
    public final function setIntScalarTypeResolver($intScalarTypeResolver) : void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    protected final function getIntScalarTypeResolver() : IntScalarTypeResolver
    {
        /** @var IntScalarTypeResolver */
        return $this->intScalarTypeResolver = $this->intScalarTypeResolver ?? $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [UserObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['avatar'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'avatar':
                return $this->__('User avatar', 'user-avatars');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        switch ($fieldName) {
            case 'avatar':
                return ['size' => $this->getIntScalarTypeResolver()];
            default:
                return parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['avatar' => 'size']:
                return $this->__('Avatar size', 'user-avatars');
            default:
                return parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName)
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        switch ([$fieldName => $fieldArgName]) {
            case ['avatar' => 'size']:
                return $moduleConfiguration->getUserAvatarDefaultSize();
            default:
                return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['avatar' => 'size']:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        switch ($fieldDataAccessor->getFieldName()) {
            case 'avatar':
                // Create the avatar, and store it in the dynamic registry
                $avatarSize = $fieldDataAccessor->getValue('size') ?? $moduleConfiguration->getUserAvatarDefaultSize();
                $avatarSrc = $this->getUserAvatarTypeAPI()->getUserAvatarSrc($user, $avatarSize);
                if ($avatarSrc === null) {
                    return null;
                }
                $avatarIDComponents = ['src' => $avatarSrc, 'size' => $avatarSize];
                // Generate a hash to represent the ID of the avatar given its properties
                $avatarID = \hash('md5', (string) \json_encode($avatarIDComponents));
                $this->getUserAvatarRuntimeRegistry()->storeUserAvatar(new UserAvatar($avatarID, $avatarSrc, $avatarSize));
                return $avatarID;
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'avatar':
                return $this->getUserAvatarObjectTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserAvatars\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\UserAvatars\ObjectModels\UserAvatar;
use PoPCMSSchema\UserAvatars\RelationalTypeDataLoaders\ObjectType\UserAvatarTypeDataLoader;
class UserAvatarObjectTypeResolver extends AbstractObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\UserAvatars\RelationalTypeDataLoaders\ObjectType\UserAvatarTypeDataLoader|null
     */
    private $userAvatarTypeDataLoader;
    /**
     * @param \PoPCMSSchema\UserAvatars\RelationalTypeDataLoaders\ObjectType\UserAvatarTypeDataLoader $userAvatarTypeDataLoader
     */
    public final function setUserAvatarTypeDataLoader($userAvatarTypeDataLoader) : void
    {
        $this->userAvatarTypeDataLoader = $userAvatarTypeDataLoader;
    }
    protected final function getUserAvatarTypeDataLoader() : UserAvatarTypeDataLoader
    {
        /** @var UserAvatarTypeDataLoader */
        return $this->userAvatarTypeDataLoader = $this->userAvatarTypeDataLoader ?? $this->instanceManager->getInstance(UserAvatarTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'UserAvatar';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('User avatar', 'user-avatars');
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        /** @var UserAvatar */
        $userAvatar = $object;
        return $userAvatar->id;
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getUserAvatarTypeDataLoader();
    }
}

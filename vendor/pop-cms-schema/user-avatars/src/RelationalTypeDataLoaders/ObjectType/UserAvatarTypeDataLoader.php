<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserAvatars\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoPCMSSchema\UserAvatars\RuntimeRegistries\UserAvatarRuntimeRegistryInterface;
class UserAvatarTypeDataLoader extends AbstractObjectTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\UserAvatars\RuntimeRegistries\UserAvatarRuntimeRegistryInterface|null
     */
    private $userAvatarRuntimeRegistry;
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
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects($ids) : array
    {
        return \array_map(\Closure::fromCallable([$this->getUserAvatarRuntimeRegistry(), 'getUserAvatar']), $ids);
    }
}

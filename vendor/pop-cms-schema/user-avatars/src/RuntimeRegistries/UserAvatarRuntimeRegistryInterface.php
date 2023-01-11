<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserAvatars\RuntimeRegistries;

use PoPCMSSchema\UserAvatars\ObjectModels\UserAvatar;
interface UserAvatarRuntimeRegistryInterface
{
    /**
     * @param \PoPCMSSchema\UserAvatars\ObjectModels\UserAvatar $userAvatar
     */
    public function storeUserAvatar($userAvatar) : void;
    /**
     * @param string|int $id
     */
    public function getUserAvatar($id) : ?UserAvatar;
}

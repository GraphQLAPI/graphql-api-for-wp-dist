<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserAvatars\TypeAPIs;

interface UserAvatarTypeAPIInterface
{
    /**
     * @param string|int|object $userObjectOrID
     * @param int $size
     */
    public function getUserAvatarSrc($userObjectOrID, $size = 150) : ?string;
}

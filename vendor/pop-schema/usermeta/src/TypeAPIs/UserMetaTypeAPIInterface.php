<?php

declare (strict_types=1);
namespace PoPSchema\UserMeta\TypeAPIs;

interface UserMetaTypeAPIInterface
{
    /**
     * @param string|int $userID
     * @return mixed
     */
    public function getUserMeta($userID, string $key, bool $single = \false);
}

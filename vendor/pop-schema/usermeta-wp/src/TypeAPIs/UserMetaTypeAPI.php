<?php

declare(strict_types=1);

namespace PoPSchema\UserMetaWP\TypeAPIs;

use PoPSchema\UserMeta\TypeAPIs\AbstractUserMetaTypeAPI;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserMetaTypeAPI extends AbstractUserMetaTypeAPI
{
    /**
     * @param string|int $userID
     * @return mixed
     */
    public function doGetUserMeta($userID, string $key, bool $single = false)
    {
        return \get_user_meta($userID, $key, $single);
    }
}

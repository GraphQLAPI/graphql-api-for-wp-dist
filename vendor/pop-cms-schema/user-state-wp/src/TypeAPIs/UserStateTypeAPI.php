<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateWP\TypeAPIs;

use PoPCMSSchema\UserState\TypeAPIs\UserStateTypeAPIInterface;

use function get_current_user_id;
use function is_user_logged_in;
use function wp_get_current_user;

class UserStateTypeAPI implements UserStateTypeAPIInterface
{
    public function isUserLoggedIn(): bool
    {
        return is_user_logged_in();
    }
    public function getCurrentUser()
    {
        return wp_get_current_user();
    }
    /**
     * @return string|int|null
     */
    public function getCurrentUserID()
    {
        return get_current_user_id();
    }
}

<?php

declare(strict_types=1);

namespace PoPCMSSchema\UsersWP\ConditionalOnModule\CustomPosts\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\Constants\UserOrderBy;
use PoPCMSSchema\UsersWP\TypeAPIs\UserTypeAPI;

class UserQueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            UserTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE,
            \Closure::fromCallable([$this, 'getOrderByQueryArgValue'])
        );
    }

    /**
     * @param string $orderBy
     */
    public function getOrderByQueryArgValue($orderBy): string
    {
        switch ($orderBy) {
            case UserOrderBy::CUSTOMPOST_COUNT:
                return 'post_count';
            default:
                return $orderBy;
        }
    }
}

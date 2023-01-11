<?php

declare(strict_types=1);

namespace PoPWPSchema\Users\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\UsersWP\TypeAPIs\UserTypeAPI;
use PoPWPSchema\Users\Constants\UserOrderBy;

class QueryHookSet extends AbstractHookSet
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
            case UserOrderBy::INCLUDE:
                return 'include';
            case UserOrderBy::WEBSITE_URL:
                return 'user_url';
            case UserOrderBy::NICENAME:
                return 'user_nicename';
            case UserOrderBy::EMAIL:
                return 'user_email';
            default:
                return $orderBy;
        }
    }
}

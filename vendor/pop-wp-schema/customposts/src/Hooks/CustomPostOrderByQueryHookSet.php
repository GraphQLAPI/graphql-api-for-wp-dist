<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use PoPWPSchema\CustomPosts\Constants\CustomPostOrderBy;

class CustomPostOrderByQueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            AbstractCustomPostTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE,
            \Closure::fromCallable([$this, 'getOrderByQueryArgValue'])
        );
    }

    /**
     * @param string $orderBy
     */
    public function getOrderByQueryArgValue($orderBy): string
    {
        switch ($orderBy) {
            case CustomPostOrderBy::NONE:
                return 'none';
            case CustomPostOrderBy::COMMENT_COUNT:
                return 'comment_count';
            case CustomPostOrderBy::RANDOM:
                return 'rand';
            case CustomPostOrderBy::MODIFIED_DATE:
                return 'modified';
            case CustomPostOrderBy::RELEVANCE:
                return 'relevance';
            case CustomPostOrderBy::TYPE:
                return 'type';
            case CustomPostOrderBy::PARENT:
                return 'parent';
            case CustomPostOrderBy::MENU_ORDER:
                return 'menu_order';
            default:
                return $orderBy;
        }
    }
}

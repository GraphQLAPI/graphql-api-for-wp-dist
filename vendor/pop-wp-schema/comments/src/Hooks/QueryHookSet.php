<?php

declare(strict_types=1);

namespace PoPWPSchema\Comments\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CommentsWP\TypeAPIs\CommentTypeAPI;
use PoPWPSchema\Comments\Constants\CommentOrderBy;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            CommentTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE,
            \Closure::fromCallable([$this, 'getOrderByQueryArgValue'])
        );
    }

    /**
     * @param string $orderBy
     */
    public function getOrderByQueryArgValue($orderBy): string
    {
        switch ($orderBy) {
            case CommentOrderBy::AUTHOR_EMAIL:
                return 'comment_author_email';
            case CommentOrderBy::AUTHOR_IP:
                return 'comment_author_IP';
            case CommentOrderBy::AUTHOR_URL:
                return 'comment_author_url';
            case CommentOrderBy::KARMA:
                return 'comment_karma';
            case CommentOrderBy::NONE:
                return 'none';
            default:
                return $orderBy;
        }
    }
}

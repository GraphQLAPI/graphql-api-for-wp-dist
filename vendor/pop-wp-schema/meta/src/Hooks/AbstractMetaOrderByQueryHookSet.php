<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPWPSchema\Meta\Constants\MetaOrderBy;

abstract class AbstractMetaOrderByQueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            $this->getHookName(),
            \Closure::fromCallable([$this, 'getOrderByQueryArgValue'])
        );
    }

    abstract protected function getHookName(): string;

    /**
     * @param string $orderBy
     */
    public function getOrderByQueryArgValue($orderBy): string
    {
        switch ($orderBy) {
            case MetaOrderBy::META_VALUE:
                return 'meta_value';
            default:
                return $orderBy;
        }
    }
}

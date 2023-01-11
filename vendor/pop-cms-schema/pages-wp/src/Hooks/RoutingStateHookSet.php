<?php

declare(strict_types=1);

namespace PoPCMSSchema\PagesWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\RootWP\Routing\HookNames;
use PoPCMSSchema\Pages\Routing\RequestNature;
use WP_Query;

class RoutingStateHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookNames::NATURE,
            \Closure::fromCallable([$this, 'getNature']),
            10,
            2
        );
    }

    /**
     * The nature of the route
     * @param string $nature
     * @param \WP_Query $query
     */
    public function getNature($nature, $query): string
    {
        if ($query->is_page()) {
            return RequestNature::PAGE;
        }

        return $nature;
    }
}

<?php

namespace PrefixedByPoP;

use PoP\Hooks\Facades\HooksAPIFacade;
\PoP\Hooks\Facades\HooksAPIFacade::getInstance()->addFilter('routes', function ($routes) {
    return \array_merge($routes, [\POP_USERS_ROUTE_USERS]);
});

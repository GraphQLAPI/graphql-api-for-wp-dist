<?php

declare (strict_types=1);
namespace PoPSchema\Posts\Conditional\Users\Conditional\RESTAPI\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\Users\Conditional\RESTAPI\RouteModuleProcessors\EntryRouteModuleProcessor;
class PostHooks extends \PoP\Hooks\AbstractHookSet
{
    public const USER_RESTFIELDS = 'posts.id|title|date|url';
    protected function init()
    {
        $this->hooksAPI->addFilter(\PoPSchema\Users\Conditional\RESTAPI\RouteModuleProcessors\EntryRouteModuleProcessor::HOOK_REST_FIELDS, [$this, 'getRESTFields']);
    }
    public function getRESTFields($restFields) : string
    {
        return $restFields . ',' . self::USER_RESTFIELDS;
    }
}

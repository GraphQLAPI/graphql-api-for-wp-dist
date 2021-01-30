<?php

declare (strict_types=1);
namespace PoPSchema\PostTags\Conditional\RESTAPI\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\Posts\Conditional\RESTAPI\RouteModuleProcessorHelpers\EntryRouteModuleProcessorHelpers;
class PostHooks extends \PoP\Hooks\AbstractHookSet
{
    const TAG_RESTFIELDS = 'tags.id|name|url';
    protected function init()
    {
        $this->hooksAPI->addFilter(\PoPSchema\Posts\Conditional\RESTAPI\RouteModuleProcessorHelpers\EntryRouteModuleProcessorHelpers::HOOK_REST_FIELDS, [$this, 'getRESTFields']);
    }
    public function getRESTFields($restFields) : string
    {
        return $restFields . ',' . self::TAG_RESTFIELDS;
    }
}

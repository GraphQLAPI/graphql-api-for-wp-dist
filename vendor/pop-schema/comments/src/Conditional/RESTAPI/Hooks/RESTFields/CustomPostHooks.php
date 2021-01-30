<?php

declare (strict_types=1);
namespace PoPSchema\Comments\Conditional\RESTAPI\Hooks\RESTFields;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPosts\Conditional\RESTAPI\RouteModuleProcessorHelpers\EntryRouteModuleProcessorHelpers;
class CustomPostHooks extends \PoP\Hooks\AbstractHookSet
{
    const COMMENT_RESTFIELDS = 'comments.id|content';
    protected function init()
    {
        $this->hooksAPI->addFilter(\PoPSchema\CustomPosts\Conditional\RESTAPI\RouteModuleProcessorHelpers\EntryRouteModuleProcessorHelpers::HOOK_REST_FIELDS, [$this, 'getRESTFields']);
    }
    public function getRESTFields($restFields) : string
    {
        return $restFields . ',' . self::COMMENT_RESTFIELDS;
    }
}

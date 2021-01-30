<?php

declare (strict_types=1);
namespace PoPSchema\Comments\Hooks;

use PoPSchema\Comments\Constants\Params;
use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\ModuleProcessors\Constants;
class WhitelistParamHooks extends \PoP\Hooks\AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addFilter(\PoP\ComponentModel\ModuleProcessors\Constants::HOOK_QUERYDATA_WHITELISTEDPARAMS, array($this, 'getWhitelistedParams'));
    }
    public function getWhitelistedParams(array $params) : array
    {
        // Used for the Comments to know what post to fetch comments from when filtering
        $params[] = \PoPSchema\Comments\Constants\Params::COMMENT_POST_ID;
        return $params;
    }
}

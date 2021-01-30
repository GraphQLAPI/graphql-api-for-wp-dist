<?php

declare (strict_types=1);
namespace PoP\AccessControl\Hooks;

use PoP\AccessControl\ComponentConfiguration;
use PoP\AccessControl\Hooks\AbstractAccessControlForDirectivesHookSet;
use PoP\AccessControl\Schema\SchemaModes;
abstract class AbstractAccessControlForDirectivesInPrivateSchemaHookSet extends \PoP\AccessControl\Hooks\AbstractAccessControlForDirectivesHookSet
{
    protected function enabled() : bool
    {
        return \PoP\AccessControl\ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode() || \PoP\AccessControl\ComponentConfiguration::usePrivateSchemaMode();
    }
    protected function getSchemaMode() : string
    {
        return \PoP\AccessControl\Schema\SchemaModes::PRIVATE_SCHEMA_MODE;
    }
}

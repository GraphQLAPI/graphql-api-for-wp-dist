<?php

declare (strict_types=1);
namespace PoP\AccessControl\Hooks;

use PoP\AccessControl\ComponentConfiguration;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\AccessControl\Hooks\AbstractAccessControlForFieldsHookSet;
abstract class AbstractAccessControlForFieldsInPrivateSchemaHookSet extends \PoP\AccessControl\Hooks\AbstractAccessControlForFieldsHookSet
{
    /**
     * Indicate if this hook is enabled
     *
     * @return boolean
     */
    protected function enabled() : bool
    {
        return \PoP\AccessControl\ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode() || \PoP\AccessControl\ComponentConfiguration::usePrivateSchemaMode();
    }
    protected function getSchemaMode() : string
    {
        return \PoP\AccessControl\Schema\SchemaModes::PRIVATE_SCHEMA_MODE;
    }
}

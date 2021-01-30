<?php

declare (strict_types=1);
namespace PoP\AccessControl\ConfigurationEntries;

use PoP\AccessControl\ComponentConfiguration;
use PoP\AccessControl\Schema\SchemaModes;
trait AccessControlConfigurableMandatoryDirectivesForItemsTrait
{
    /**
     * Indicates if the entry having no schema mode set must also be processed
     * To decide, it gets the default schema mode and checks its the same with
     * the one from this object
     *
     * @return bool
     */
    protected function doesSchemaModeProcessNullControlEntry() : bool
    {
        $individualControlSchemaMode = $this->getSchemaMode();
        return \PoP\AccessControl\ComponentConfiguration::usePrivateSchemaMode() && $individualControlSchemaMode == \PoP\AccessControl\Schema\SchemaModes::PRIVATE_SCHEMA_MODE || !\PoP\AccessControl\ComponentConfiguration::usePrivateSchemaMode() && $individualControlSchemaMode == \PoP\AccessControl\Schema\SchemaModes::PUBLIC_SCHEMA_MODE;
    }
    protected abstract function getSchemaMode() : string;
}

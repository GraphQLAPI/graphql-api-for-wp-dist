<?php

declare (strict_types=1);
namespace PoP\AccessControl\TypeResolverDecorators;

use PoP\AccessControl\Services\AccessControlGroups;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoP\AccessControl\TypeResolverDecorators\AbstractDisableAccessConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator;
class DisableAccessConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator extends \PoP\AccessControl\TypeResolverDecorators\AbstractDisableAccessConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator
{
    protected static function getConfigurationEntries() : array
    {
        $accessControlManager = \PoP\AccessControl\Facades\AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForFields(\PoP\AccessControl\Services\AccessControlGroups::DISABLED);
    }
}

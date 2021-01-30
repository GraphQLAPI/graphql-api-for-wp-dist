<?php

declare (strict_types=1);
namespace PoP\AccessControl\TypeResolverDecorators;

use PoP\AccessControl\Services\AccessControlGroups;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoP\AccessControl\TypeResolverDecorators\AbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator;
class DisableAccessConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator extends \PoP\AccessControl\TypeResolverDecorators\AbstractDisableAccessConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator
{
    protected function getConfigurationEntries() : array
    {
        $accessControlManager = \PoP\AccessControl\Facades\AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForDirectives(\PoP\AccessControl\Services\AccessControlGroups::DISABLED);
    }
}

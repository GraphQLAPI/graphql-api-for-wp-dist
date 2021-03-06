<?php

declare (strict_types=1);
namespace PoPSchema\UserStateAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPrivateSchemaTypeResolverDecorator;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoPSchema\UserStateAccessControl\Services\AccessControlGroups;
abstract class AbstractNoCacheConfigurableAccessControlForFieldsInPrivateSchemaTypeResolverDecorator extends \PoP\AccessControl\TypeResolverDecorators\AbstractConfigurableAccessControlForFieldsInPrivateSchemaTypeResolverDecorator
{
    use NoCacheConfigurableAccessControlTypeResolverDecoratorTrait;
    protected static function getConfigurationEntries() : array
    {
        $accessControlManager = \PoP\AccessControl\Facades\AccessControlManagerFacade::getInstance();
        return $accessControlManager->getEntriesForFields(\PoPSchema\UserStateAccessControl\Services\AccessControlGroups::STATE);
    }
}

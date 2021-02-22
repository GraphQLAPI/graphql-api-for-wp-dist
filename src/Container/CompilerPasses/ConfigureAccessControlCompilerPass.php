<?php

declare (strict_types=1);
namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use PoP\AccessControl\Services\AccessControlManagerInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use PoPSchema\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;
class ConfigureAccessControlCompilerPass implements \PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface
{
    public function process(\PrefixedByPoP\Symfony\Component\DependencyInjection\ContainerBuilder $containerBuilder) : void
    {
        $accessControlManagerDefinition = $containerBuilder->getDefinition(\PoP\AccessControl\Services\AccessControlManagerInterface::class);
        $schemaEditorAccessCapability = \GraphQLAPI\GraphQLAPI\Security\UserAuthorization::getSchemaEditorAccessCapability();
        $capabilities = [$schemaEditorAccessCapability];
        $accessControlManagerDefinition->addMethodCall('addEntriesForFields', [\PoPSchema\UserRolesAccessControl\Services\AccessControlGroups::CAPABILITIES, [[\PoP\Engine\TypeResolvers\RootTypeResolver::class, 'accessControlLists', $capabilities], [\PoP\Engine\TypeResolvers\RootTypeResolver::class, 'cacheControlLists', $capabilities], [\PoP\Engine\TypeResolvers\RootTypeResolver::class, 'fieldDeprecationLists', $capabilities]]]);
    }
}

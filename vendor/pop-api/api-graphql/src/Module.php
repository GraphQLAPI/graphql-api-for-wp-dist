<?php

declare (strict_types=1);
namespace PoPAPI\GraphQLAPI;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Module\AbstractModule;
class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses() : array
    {
        return [\PoPAPI\APIMirrorQuery\Module::class];
    }
    protected function resolveEnabled() : bool
    {
        return !\PoPAPI\GraphQLAPI\Environment::disableGraphQLAPI();
    }
    /**
     * Initialize services
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     * @param bool $skipSchema
     */
    protected function initializeContainerServices($skipSchema, $skipSchemaModuleClasses) : void
    {
        $this->initServices(\dirname(__DIR__));
    }
}

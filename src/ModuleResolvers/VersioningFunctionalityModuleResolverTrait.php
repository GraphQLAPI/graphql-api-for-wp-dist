<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolver;

trait VersioningFunctionalityModuleResolverTrait
{
    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int
    {
        return 130;
    }

    /**
     * Enable to customize a specific UI for the module
     * @param string $module
     */
    public function getModuleType($module): string
    {
        return ModuleTypeResolver::VERSIONING;
    }
}

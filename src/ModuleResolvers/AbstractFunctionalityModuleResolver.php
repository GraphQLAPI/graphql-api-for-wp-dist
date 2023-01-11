<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolver;

abstract class AbstractFunctionalityModuleResolver extends AbstractModuleResolver
{
    /**
     * The type of the module
     * @param string $module
     */
    public function getModuleType($module): string
    {
        return ModuleTypeResolver::FUNCTIONALITY;
    }
}

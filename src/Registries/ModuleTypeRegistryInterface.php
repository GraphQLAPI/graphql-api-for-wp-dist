<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Exception\ModuleTypeNotExistsException;
use GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolverInterface;

interface ModuleTypeRegistryInterface
{
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolverInterface $moduleTypeResolver
     */
    public function addModuleTypeResolver($moduleTypeResolver): void;
    /**
     * @throws ModuleTypeNotExistsException If module does not exist
     * @param string $moduleType
     */
    public function getModuleTypeResolver($moduleType): ModuleTypeResolverInterface;
}

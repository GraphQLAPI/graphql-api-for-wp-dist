<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Exception\ModuleTypeNotExistsException;
use GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolverInterface;

class ModuleTypeRegistry implements ModuleTypeRegistryInterface
{
    /**
     * @var array<string,ModuleTypeResolverInterface>
     */
    protected $moduleTypeResolvers = [];

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolverInterface $moduleTypeResolver
     */
    public function addModuleTypeResolver($moduleTypeResolver): void
    {
        foreach ($moduleTypeResolver->getModuleTypesToResolve() as $moduleType) {
            $this->moduleTypeResolvers[$moduleType] = $moduleTypeResolver;
        }
    }

    /**
     * @throws ModuleTypeNotExistsException If module does not exist
     * @param string $moduleType
     */
    public function getModuleTypeResolver($moduleType): ModuleTypeResolverInterface
    {
        if (!isset($this->moduleTypeResolvers[$moduleType])) {
            throw new ModuleTypeNotExistsException(sprintf(
                \__('Module type \'%s\' does not exist', 'graphql-api'),
                $moduleType
            ));
        }
        return $this->moduleTypeResolvers[$moduleType];
    }
}

<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers;

interface ModuleTypeResolverInterface
{
    /**
     * @return string[]
     */
    public function getModuleTypesToResolve(): array;
    /**
     * @param string $moduleType
     */
    public function getSlug($moduleType): string;
    /**
     * @param string $moduleType
     */
    public function getName($moduleType): string;
}

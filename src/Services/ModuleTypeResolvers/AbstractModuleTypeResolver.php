<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers;

abstract class AbstractModuleTypeResolver implements ModuleTypeResolverInterface
{
    /**
     * By default, the slug is the module's name, without the owner/package
     * @param string $moduleType
     */
    public function getSlug($moduleType): string
    {
        $pos = strrpos($moduleType, '\\');
        if ($pos !== false) {
            return substr($moduleType, $pos + strlen('\\'));
        }
        return $moduleType;
    }

    /**
     * Provide a default name, just in case none is provided
     * @param string $moduleType
     */
    public function getName($moduleType): string
    {
        return $this->getSlug($moduleType);
    }
}

<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\PostsWP\Module::class,
            \PoPWPSchema\CustomPosts\Module::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     * @param bool $skipSchema
     */
    protected function initializeContainerServices($skipSchema, $skipSchemaModuleClasses): void
    {
        $this->initServices(dirname(__DIR__));
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);
    }
}

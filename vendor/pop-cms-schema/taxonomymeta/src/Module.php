<?php

declare (strict_types=1);
namespace PoPCMSSchema\TaxonomyMeta;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Module\AbstractModule;
class Module extends AbstractModule
{
    protected function requiresSatisfyingModule() : bool
    {
        return \true;
    }
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses() : array
    {
        return [\PoPCMSSchema\Meta\Module::class, \PoPCMSSchema\Taxonomies\Module::class];
    }
    /**
     * Initialize services
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     * @param bool $skipSchema
     */
    protected function initializeContainerServices($skipSchema, $skipSchemaModuleClasses) : void
    {
        $this->initSchemaServices(\dirname(__DIR__), $skipSchema);
    }
}

<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ModuleProcessors;

trait DataloadQueryArgsSchemaFilterInputModuleProcessorTrait
{
    use FilterInputModuleProcessorTrait;
    use SchemaFilterInputModuleProcessorTrait;
    public function getFilterInputSchemaDefinitionResolver(array $module) : ?\PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
    {
        return $this;
    }
}

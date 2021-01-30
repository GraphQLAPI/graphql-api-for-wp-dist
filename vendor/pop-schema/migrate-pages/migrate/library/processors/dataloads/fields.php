<?php

namespace PrefixedByPoP;

use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoPSchema\QueriedObject\ModuleProcessors\QueriedDBObjectModuleProcessorTrait;
use PoPSchema\Pages\TypeResolvers\PageTypeResolver;
class PoP_Pages_Module_Processor_FieldDataloads extends \PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor
{
    use QueriedDBObjectModuleProcessorTrait;
    public const MODULE_DATALOAD_RELATIONALFIELDS_PAGE = 'dataload-relationalfields-page';
    public function getModulesToProcess() : array
    {
        return array([self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_PAGE]);
    }
    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_PAGE:
                return $this->getQueriedDBObjectID($module, $props, $data_properties);
        }
        return parent::getDBObjectIDOrIDs($module, $props, $data_properties);
    }
    public function getTypeResolverClass(array $module) : ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_PAGE:
                return \PoPSchema\Pages\TypeResolvers\PageTypeResolver::class;
        }
        return parent::getTypeResolverClass($module);
    }
}
\class_alias('PrefixedByPoP\\PoP_Pages_Module_Processor_FieldDataloads', 'PoP_Pages_Module_Processor_FieldDataloads', \false);

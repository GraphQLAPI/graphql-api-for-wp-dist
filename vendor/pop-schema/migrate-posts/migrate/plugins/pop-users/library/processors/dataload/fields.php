<?php

namespace PrefixedByPoP;

use PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostTypeResolver;
use PoP\ComponentModel\State\ApplicationState;
class PoP_Users_Posts_Module_Processor_FieldDataloads extends \PoP\API\ModuleProcessors\AbstractRelationalFieldDataloadModuleProcessor
{
    public const MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST = 'dataload-relationalfields-authorpostlist';
    public function getModulesToProcess() : array
    {
        return array([self::class, self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST]);
    }
    public function getTypeResolverClass(array $module) : ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
                return \PoPSchema\CustomPosts\TypeResolvers\CustomPostTypeResolver::class;
        }
        return parent::getTypeResolverClass($module);
    }
    public function getQueryInputOutputHandlerClass(array $module) : ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
                return \PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler::class;
        }
        return parent::getQueryInputOutputHandlerClass($module);
    }
    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props) : array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
                $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
                $ret['authors'] = [$vars['routing-state']['queried-object-id']];
                break;
        }
        return $ret;
    }
    public function getFilterSubmodule(array $module) : ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_AUTHORPOSTLIST:
                return [\PrefixedByPoP\PoP_Posts_Module_Processor_CustomFilterInners::class, \PrefixedByPoP\PoP_Posts_Module_Processor_CustomFilterInners::MODULE_FILTERINNER_POSTS];
        }
        return parent::getFilterSubmodule($module);
    }
}
\class_alias('PrefixedByPoP\\PoP_Users_Posts_Module_Processor_FieldDataloads', 'PoP_Users_Posts_Module_Processor_FieldDataloads', \false);

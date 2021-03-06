<?php

namespace PrefixedByPoP;

use PoP\Engine\FilterInputProcessor;
use PoP\ComponentModel\PoP_InputUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
class PoP_Module_Processor_FilterInputs extends \PoP\ComponentModel\AbstractFormInputs implements \PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface, \PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
    public const MODULE_FILTERINPUT_ORDER = 'filterinput-order';
    public const MODULE_FILTERINPUT_LIMIT = 'filterinput-limit';
    public const MODULE_FILTERINPUT_OFFSET = 'filterinput-offset';
    public const MODULE_FILTERINPUT_SEARCH = 'filterinput-search';
    public const MODULE_FILTERINPUT_DATES = 'filterinput-dates';
    public const MODULE_FILTERINPUT_IDS = 'filterinput-ids';
    public const MODULE_FILTERINPUT_ID = 'filterinput-id';
    public function getModulesToProcess() : array
    {
        return array([self::class, self::MODULE_FILTERINPUT_ORDER], [self::class, self::MODULE_FILTERINPUT_LIMIT], [self::class, self::MODULE_FILTERINPUT_OFFSET], [self::class, self::MODULE_FILTERINPUT_SEARCH], [self::class, self::MODULE_FILTERINPUT_DATES], [self::class, self::MODULE_FILTERINPUT_IDS], [self::class, self::MODULE_FILTERINPUT_ID]);
    }
    public function getFilterInput(array $module) : ?array
    {
        $filterInputs = [self::MODULE_FILTERINPUT_ORDER => [\PoP\Engine\FilterInputProcessor::class, \PoP\Engine\FilterInputProcessor::FILTERINPUT_ORDER], self::MODULE_FILTERINPUT_LIMIT => [\PoP\Engine\FilterInputProcessor::class, \PoP\Engine\FilterInputProcessor::FILTERINPUT_LIMIT], self::MODULE_FILTERINPUT_OFFSET => [\PoP\Engine\FilterInputProcessor::class, \PoP\Engine\FilterInputProcessor::FILTERINPUT_OFFSET], self::MODULE_FILTERINPUT_SEARCH => [\PoP\Engine\FilterInputProcessor::class, \PoP\Engine\FilterInputProcessor::FILTERINPUT_SEARCH], self::MODULE_FILTERINPUT_DATES => [\PoP\Engine\FilterInputProcessor::class, \PoP\Engine\FilterInputProcessor::FILTERINPUT_DATES], self::MODULE_FILTERINPUT_IDS => [\PoP\Engine\FilterInputProcessor::class, \PoP\Engine\FilterInputProcessor::FILTERINPUT_INCLUDE], self::MODULE_FILTERINPUT_ID => [\PoP\Engine\FilterInputProcessor::class, \PoP\Engine\FilterInputProcessor::FILTERINPUT_INCLUDE]];
        return $filterInputs[$module[1]] ?? null;
    }
    public function getInputName(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_DATES:
                // Allow for multiple names, for multiple inputs
                $name = $this->getName($module);
                $names = array();
                foreach ($this->getInputSubnames($module) as $subname) {
                    $names[$subname] = \PoP\ComponentModel\PoP_InputUtils::getMultipleinputsName($name, $subname) . ($this->isMultiple($module) ? '[]' : '');
                }
                return $names;
        }
        return parent::getInputName($module);
    }
    public function getInputOptions(array $module)
    {
        $options = parent::getInputOptions($module);
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_DATES:
                $options['subnames'] = ['from', 'to'];
                break;
        }
        return $options;
    }
    public function getInputClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_ORDER:
                return \PoP\Engine\GD_FormInput_Order::class;
            case self::MODULE_FILTERINPUT_DATES:
                return \PoP\Engine\GD_FormInput_MultipleInputs::class;
            case self::MODULE_FILTERINPUT_IDS:
                return \PoP\ComponentModel\GD_FormInput_MultiInput::class;
            case self::MODULE_FILTERINPUT_ID:
                return \PoP\Engine\GD_FormInput_MultiValueFromString::class;
        }
        return parent::getInputClass($module);
    }
    public function getName(array $module)
    {
        // Add a nice name, so that the URL params when filtering make sense
        $names = array(self::MODULE_FILTERINPUT_ORDER => 'order', self::MODULE_FILTERINPUT_LIMIT => 'limit', self::MODULE_FILTERINPUT_OFFSET => 'offset', self::MODULE_FILTERINPUT_SEARCH => 'searchfor', self::MODULE_FILTERINPUT_DATES => 'date', self::MODULE_FILTERINPUT_IDS => 'ids', self::MODULE_FILTERINPUT_ID => 'id');
        return $names[$module[1]] ?? parent::getName($module);
    }
    protected function modifyFilterSchemaDefinitionItems(array &$schemaDefinitionItems, array $module)
    {
        // Replace the "date" item with "date-from" and "date-to"
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_DATES:
                $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
                $name = $this->getName($module);
                $subnames = $this->getInputOptions($module)['subnames'];
                $dateFormat = 'Y-m-d';
                // Save documentation as template, and remove it
                $schemaDefinition = $schemaDefinitionItems[0];
                unset($schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME]);
                unset($schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION]);
                \array_shift($schemaDefinitionItems);
                // Add the other elements, using the original documantation as placeholder
                $schemaDefinitionItems[] = \array_merge([\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoP\ComponentModel\PoP_InputUtils::getMultipleinputsName($name, $subnames[0])], $schemaDefinition, [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => \sprintf($translationAPI->__('Search for elements starting from this date, in format \'%s\'', 'pop-engine'), $dateFormat)]);
                $schemaDefinitionItems[] = \array_merge([\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoP\ComponentModel\PoP_InputUtils::getMultipleinputsName($name, $subnames[1])], $schemaDefinition, [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => \sprintf($translationAPI->__('Search for elements starting until this date, in format \'%s\'', 'pop-engine'), $dateFormat)]);
                break;
        }
    }
    public function getSchemaFilterInputType(array $module) : ?string
    {
        $types = [self::MODULE_FILTERINPUT_ORDER => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, self::MODULE_FILTERINPUT_LIMIT => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_INT, self::MODULE_FILTERINPUT_OFFSET => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_INT, self::MODULE_FILTERINPUT_SEARCH => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, self::MODULE_FILTERINPUT_DATES => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_DATE, self::MODULE_FILTERINPUT_IDS => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID), self::MODULE_FILTERINPUT_ID => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID];
        return $types[$module[1]] ?? parent::getSchemaFilterInputType($module);
    }
    public function getSchemaFilterInputDescription(array $module) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_DATES:
                $name = $this->getName($module);
                $subnames = $this->getInputOptions($module)['subnames'];
                $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
                return \sprintf($translationAPI->__('Search for elements between the \'from\' and \'to\' dates. Provide dates through params \'%s\' and \'%s\', in format \'%s\'', 'pop-engine'), \PoP\ComponentModel\PoP_InputUtils::getMultipleinputsName($name, $subnames[0]), \PoP\ComponentModel\PoP_InputUtils::getMultipleinputsName($name, $subnames[1]), $cmsengineapi->getOption(\PoP\LooseContracts\Facades\NameResolverFacade::getInstance()->getName('popcms:option:dateFormat')));
        }
        $descriptions = [self::MODULE_FILTERINPUT_ORDER => $translationAPI->__('Order the results. Specify the \'orderby\' and \'order\' (\'ASC\' or \'DESC\') fields in this format: \'orderby|order\'', 'pop-engine'), self::MODULE_FILTERINPUT_LIMIT => $translationAPI->__('Limit the results. \'-1\' brings all the results (or the maximum amount allowed)', 'pop-engine'), self::MODULE_FILTERINPUT_OFFSET => $translationAPI->__('Offset the results by how many places (required for pagination)', 'pop-engine'), self::MODULE_FILTERINPUT_SEARCH => $translationAPI->__('Search for elements containing the given string', 'pop-engine'), self::MODULE_FILTERINPUT_IDS => \sprintf($translationAPI->__('Limit results to elements with the given IDs', 'pop-engine'), \PoP\ComponentModel\Tokens\Param::VALUE_SEPARATOR), self::MODULE_FILTERINPUT_ID => \sprintf($translationAPI->__('Limit results to elements with the given ID, or IDs (separated by \'%s\')', 'pop-engine'), \PoP\ComponentModel\Tokens\Param::VALUE_SEPARATOR)];
        return $descriptions[$module[1]] ?? parent::getSchemaFilterInputDescription($module);
    }
}
\class_alias('PrefixedByPoP\\PoP_Module_Processor_FilterInputs', 'PoP_Module_Processor_FilterInputs', \false);

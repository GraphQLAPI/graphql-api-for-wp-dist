<?php

declare (strict_types=1);
namespace PoPCMSSchema\SchemaCommons\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;
class CommonFilterInputContainerComponentProcessor extends \PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';
    public const COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_ID = 'filterinputcontainer-entity-by-id';
    public const COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_SLUG = 'filterinputcontainer-entity-by-slug';
    public const COMPONENT_FILTERINPUTCONTAINER_DATE_AS_STRING = 'filterinputcontainer-date-as-string';
    public const COMPONENT_FILTERINPUTCONTAINER_GMTDATE = 'filterinputcontainer-utcdate';
    public const COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING = 'filterinputcontainer-utcdate-as-string';
    /**
     * @var \PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface|null
     */
    private $cmsService;
    /**
     * @param \PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface $cmsService
     */
    public final function setCMSService($cmsService) : void
    {
        $this->cmsService = $cmsService;
    }
    protected final function getCMSService() : CMSServiceInterface
    {
        /** @var CMSServiceInterface */
        return $this->cmsService = $this->cmsService ?? $this->instanceManager->getInstance(CMSServiceInterface::class);
    }
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_ID, self::COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_SLUG, self::COMPONENT_FILTERINPUTCONTAINER_DATE_AS_STRING, self::COMPONENT_FILTERINPUTCONTAINER_GMTDATE, self::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING);
    }
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputComponents($component) : array
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_ID:
                return [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID)];
            case self::COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_SLUG:
                return [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUG)];
            case self::COMPONENT_FILTERINPUTCONTAINER_DATE_AS_STRING:
                return [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_DATEFORMAT)];
            case self::COMPONENT_FILTERINPUTCONTAINER_GMTDATE:
                return [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_GMT)];
            case self::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING:
                return [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_DATEFORMAT), new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_GMT)];
            default:
                return [];
        }
    }
    /**
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     * @param string $fieldArgName
     */
    public function getFieldFilterInputDefaultValue($component, $fieldArgName)
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_DATE_AS_STRING:
            case self::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING:
                $formatFilterInputName = FilterInputHelper::getFilterInputName(new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_DATEFORMAT));
                if ($fieldArgName === $formatFilterInputName) {
                    return $this->getCMSService()->getOption($this->getNameResolver()->getName('popcms:option:dateFormat'));
                }
                break;
        }
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_GMTDATE:
            case self::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING:
                $gmtFilterInputName = FilterInputHelper::getFilterInputName(new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_GMT));
                if ($fieldArgName === $gmtFilterInputName) {
                    return \false;
                }
                break;
        }
        return parent::getFieldFilterInputDefaultValue($component, $fieldArgName);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     * @param string $fieldArgName
     */
    public function getFieldFilterInputTypeModifiers($component, $fieldArgName) : int
    {
        $fieldFilterInputTypeModifiers = parent::getFieldFilterInputTypeModifiers($component, $fieldArgName);
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_ID:
                $idFilterInputName = FilterInputHelper::getFilterInputName(new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID));
                if ($fieldArgName === $idFilterInputName) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                break;
            case self::COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_SLUG:
                $slugFilterInputName = FilterInputHelper::getFilterInputName(new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUG));
                if ($fieldArgName === $slugFilterInputName) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                break;
        }
        return $fieldFilterInputTypeModifiers;
    }
    /**
     * @return string[]
     */
    protected function getFilterInputHookNames() : array
    {
        $item0Unpacked = parent::getFilterInputHookNames();
        return \array_merge($item0Unpacked, [self::HOOK_FILTER_INPUTS]);
    }
}

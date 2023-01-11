<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\CustomPosts\FilterInputs\CustomPostStatusFilterInput;
use PoPCMSSchema\CustomPosts\FilterInputs\UnionCustomPostTypesFilterInput;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumStringScalarTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;
class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public const COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS = 'filterinput-custompoststatus';
    public const COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES = 'filterinput-unioncustomposttypes';
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver|null
     */
    private $filterCustomPostStatusEnumTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumStringScalarTypeResolver|null
     */
    private $customPostEnumStringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\FilterInputs\CustomPostStatusFilterInput|null
     */
    private $customPostStatusFilterInput;
    /**
     * @var \PoPCMSSchema\CustomPosts\FilterInputs\UnionCustomPostTypesFilterInput|null
     */
    private $unionCustomPostTypesFilterInput;
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver
     */
    public final function setFilterCustomPostStatusEnumTypeResolver($filterCustomPostStatusEnumTypeResolver) : void
    {
        $this->filterCustomPostStatusEnumTypeResolver = $filterCustomPostStatusEnumTypeResolver;
    }
    protected final function getFilterCustomPostStatusEnumTypeResolver() : FilterCustomPostStatusEnumTypeResolver
    {
        /** @var FilterCustomPostStatusEnumTypeResolver */
        return $this->filterCustomPostStatusEnumTypeResolver = $this->filterCustomPostStatusEnumTypeResolver ?? $this->instanceManager->getInstance(FilterCustomPostStatusEnumTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumStringScalarTypeResolver $customPostEnumStringScalarTypeResolver
     */
    public final function setCustomPostEnumStringScalarTypeResolver($customPostEnumStringScalarTypeResolver) : void
    {
        $this->customPostEnumStringScalarTypeResolver = $customPostEnumStringScalarTypeResolver;
    }
    protected final function getCustomPostEnumStringScalarTypeResolver() : CustomPostEnumStringScalarTypeResolver
    {
        /** @var CustomPostEnumStringScalarTypeResolver */
        return $this->customPostEnumStringScalarTypeResolver = $this->customPostEnumStringScalarTypeResolver ?? $this->instanceManager->getInstance(CustomPostEnumStringScalarTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\FilterInputs\CustomPostStatusFilterInput $customPostStatusFilterInput
     */
    public final function setCustomPostStatusFilterInput($customPostStatusFilterInput) : void
    {
        $this->customPostStatusFilterInput = $customPostStatusFilterInput;
    }
    protected final function getCustomPostStatusFilterInput() : CustomPostStatusFilterInput
    {
        /** @var CustomPostStatusFilterInput */
        return $this->customPostStatusFilterInput = $this->customPostStatusFilterInput ?? $this->instanceManager->getInstance(CustomPostStatusFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\FilterInputs\UnionCustomPostTypesFilterInput $unionCustomPostTypesFilterInput
     */
    public final function setUnionCustomPostTypesFilterInput($unionCustomPostTypesFilterInput) : void
    {
        $this->unionCustomPostTypesFilterInput = $unionCustomPostTypesFilterInput;
    }
    protected final function getUnionCustomPostTypesFilterInput() : UnionCustomPostTypesFilterInput
    {
        /** @var UnionCustomPostTypesFilterInput */
        return $this->unionCustomPostTypesFilterInput = $this->unionCustomPostTypesFilterInput ?? $this->instanceManager->getInstance(UnionCustomPostTypesFilterInput::class);
    }
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS, self::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInput($component) : ?FilterInputInterface
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS:
                return $this->getCustomPostStatusFilterInput();
            case self::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES:
                return $this->getUnionCustomPostTypesFilterInput();
            default:
                return null;
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getInputClass($component) : string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS:
            case self::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES:
                return FormMultipleInput::class;
        }
        return parent::getInputClass($component);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getName($component) : string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS:
            case self::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES:
                // Add a nice name, so that the URL params when filtering make sense
                $names = array(self::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS => 'status', self::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES => 'customPostTypes');
                return $names[$component->name];
        }
        return parent::getName($component);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputTypeResolver($component) : InputTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS:
                return $this->getFilterCustomPostStatusEnumTypeResolver();
            case self::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES:
                return $this->getCustomPostEnumStringScalarTypeResolver();
            default:
                return $this->getDefaultSchemaFilterInputTypeResolver();
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputTypeModifiers($component) : int
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS:
            case self::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES:
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return SchemaTypeModifiers::NONE;
        }
    }
    /**
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputDefaultValue($component)
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS:
                return [CustomPostStatus::PUBLISH];
            case self::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES:
                return $this->getCustomPostEnumStringScalarTypeResolver()->getConsolidatedPossibleValues();
            default:
                return null;
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputDescription($component) : ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS:
                return $this->__('Custom Post Status', 'customposts');
            case self::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES:
                return $this->__('Return results from Union of the Custom Post Types', 'customposts');
            default:
                return null;
        }
    }
}

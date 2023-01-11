<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\CustomPosts\FilterInputs\CustomPostStatusFilterInput;
use PoPCMSSchema\CustomPosts\FilterInputs\UnionCustomPostTypesFilterInput;
use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumStringScalarTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\DateQueryInputObjectTypeResolver;
abstract class AbstractCustomPostsFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\DateQueryInputObjectTypeResolver|null
     */
    private $dateQueryInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver|null
     */
    private $filterCustomPostStatusEnumTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumStringScalarTypeResolver|null
     */
    private $customPostEnumStringScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\FilterInputs\CustomPostStatusFilterInput|null
     */
    private $customPostStatusFilterInput;
    /**
     * @var \PoPCMSSchema\CustomPosts\FilterInputs\UnionCustomPostTypesFilterInput|null
     */
    private $unionCustomPostTypesFilterInput;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput|null
     */
    private $seachFilterInput;
    /**
     * @param \PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\DateQueryInputObjectTypeResolver $dateQueryInputObjectTypeResolver
     */
    public final function setDateQueryInputObjectTypeResolver($dateQueryInputObjectTypeResolver) : void
    {
        $this->dateQueryInputObjectTypeResolver = $dateQueryInputObjectTypeResolver;
    }
    protected final function getDateQueryInputObjectTypeResolver() : DateQueryInputObjectTypeResolver
    {
        /** @var DateQueryInputObjectTypeResolver */
        return $this->dateQueryInputObjectTypeResolver = $this->dateQueryInputObjectTypeResolver ?? $this->instanceManager->getInstance(DateQueryInputObjectTypeResolver::class);
    }
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
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver
     */
    public final function setStringScalarTypeResolver($stringScalarTypeResolver) : void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected final function getStringScalarTypeResolver() : StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver = $this->stringScalarTypeResolver ?? $this->instanceManager->getInstance(StringScalarTypeResolver::class);
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
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput $seachFilterInput
     */
    public final function setSearchFilterInput($seachFilterInput) : void
    {
        $this->seachFilterInput = $seachFilterInput;
    }
    protected final function getSearchFilterInput() : SearchFilterInput
    {
        /** @var SearchFilterInput */
        return $this->seachFilterInput = $this->seachFilterInput ?? $this->instanceManager->getInstance(SearchFilterInput::class);
    }
    /**
     * @return string[]
     */
    public function getSensitiveInputFieldNames() : array
    {
        $adminInputFieldNames = parent::getSensitiveInputFieldNames();
        if ($this->treatCustomPostStatusAsSensitiveData()) {
            $adminInputFieldNames[] = 'status';
        }
        return $adminInputFieldNames;
    }
    protected function treatCustomPostStatusAsSensitiveData() : bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->treatCustomPostStatusAsSensitiveData();
    }
    protected function addCustomPostInputFields() : bool
    {
        return \false;
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return \array_merge(parent::getInputFieldNameTypeResolvers(), ['status' => $this->getFilterCustomPostStatusEnumTypeResolver(), 'search' => $this->getStringScalarTypeResolver(), 'dateQuery' => $this->getDateQueryInputObjectTypeResolver()], $this->addCustomPostInputFields() ? ['customPostTypes' => $this->getCustomPostEnumStringScalarTypeResolver()] : []);
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'status':
                return $this->__('Custom post status', 'customposts');
            case 'search':
                return $this->__('Search for custom posts containing the given string', 'customposts');
            case 'dateQuery':
                return $this->__('Filter custom posts based on date', 'customposts');
            case 'customPostTypes':
                return $this->__('Filter custom posts of given types', 'customposts');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
    /**
     * @return mixed
     * @param string $inputFieldName
     */
    public function getInputFieldDefaultValue($inputFieldName)
    {
        switch ($inputFieldName) {
            case 'status':
                return [CustomPostStatus::PUBLISH];
            case 'customPostTypes':
                return $this->getCustomPostEnumStringScalarTypeResolver()->getConsolidatedPossibleValues();
            default:
                return parent::getInputFieldDefaultValue($inputFieldName);
        }
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldTypeModifiers($inputFieldName) : int
    {
        switch ($inputFieldName) {
            case 'status':
            case 'customPostTypes':
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return parent::getInputFieldTypeModifiers($inputFieldName);
        }
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldName) : ?FilterInputInterface
    {
        switch ($inputFieldName) {
            case 'status':
                return $this->getCustomPostStatusFilterInput();
            case 'search':
                return $this->getSearchFilterInput();
            case 'customPostTypes':
                return $this->getUnionCustomPostTypesFilterInput();
            default:
                return parent::getInputFieldFilterInput($inputFieldName);
        }
    }
}

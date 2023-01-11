<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\ComponentProcessors\FormInputs;

use PoPCMSSchema\Categories\FilterInputs\CategoryIDsFilterInput;
use PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\Taxonomies\FilterInputs\TaxonomyFilterInput;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public const COMPONENT_FILTERINPUT_CATEGORY_IDS = 'filterinput-category-ids';
    public const COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY = 'filterinput-generic-category-taxonomy';
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Categories\FilterInputs\CategoryIDsFilterInput|null
     */
    private $categoryIDsFilterInput;
    /**
     * @var \PoPCMSSchema\Taxonomies\FilterInputs\TaxonomyFilterInput|null
     */
    private $taxonomyFilterInput;
    /**
     * @var \PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumStringScalarTypeResolver|null
     */
    private $categoryTaxonomyEnumStringScalarTypeResolver;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver $idScalarTypeResolver
     */
    public final function setIDScalarTypeResolver($idScalarTypeResolver) : void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    protected final function getIDScalarTypeResolver() : IDScalarTypeResolver
    {
        /** @var IDScalarTypeResolver */
        return $this->idScalarTypeResolver = $this->idScalarTypeResolver ?? $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\FilterInputs\CategoryIDsFilterInput $categoryIDsFilterInput
     */
    public final function setCategoryIDsFilterInput($categoryIDsFilterInput) : void
    {
        $this->categoryIDsFilterInput = $categoryIDsFilterInput;
    }
    protected final function getCategoryIDsFilterInput() : CategoryIDsFilterInput
    {
        /** @var CategoryIDsFilterInput */
        return $this->categoryIDsFilterInput = $this->categoryIDsFilterInput ?? $this->instanceManager->getInstance(CategoryIDsFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Taxonomies\FilterInputs\TaxonomyFilterInput $taxonomyFilterInput
     */
    public final function setTaxonomyFilterInput($taxonomyFilterInput) : void
    {
        $this->taxonomyFilterInput = $taxonomyFilterInput;
    }
    protected final function getTaxonomyFilterInput() : TaxonomyFilterInput
    {
        /** @var TaxonomyFilterInput */
        return $this->taxonomyFilterInput = $this->taxonomyFilterInput ?? $this->instanceManager->getInstance(TaxonomyFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumStringScalarTypeResolver $categoryTaxonomyEnumStringScalarTypeResolver
     */
    public final function setCategoryTaxonomyEnumStringScalarTypeResolver($categoryTaxonomyEnumStringScalarTypeResolver) : void
    {
        $this->categoryTaxonomyEnumStringScalarTypeResolver = $categoryTaxonomyEnumStringScalarTypeResolver;
    }
    protected final function getCategoryTaxonomyEnumStringScalarTypeResolver() : CategoryTaxonomyEnumStringScalarTypeResolver
    {
        /** @var CategoryTaxonomyEnumStringScalarTypeResolver */
        return $this->categoryTaxonomyEnumStringScalarTypeResolver = $this->categoryTaxonomyEnumStringScalarTypeResolver ?? $this->instanceManager->getInstance(CategoryTaxonomyEnumStringScalarTypeResolver::class);
    }
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUT_CATEGORY_IDS, self::COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInput($component) : ?FilterInputInterface
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CATEGORY_IDS:
                return $this->getCategoryIDsFilterInput();
            case self::COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY:
                return $this->getTaxonomyFilterInput();
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
            case self::COMPONENT_FILTERINPUT_CATEGORY_IDS:
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
            case self::COMPONENT_FILTERINPUT_CATEGORY_IDS:
                return 'categoryIDs';
            case self::COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY:
                return 'taxonomy';
            default:
                return parent::getName($component);
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputTypeResolver($component) : InputTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CATEGORY_IDS:
                return $this->getIDScalarTypeResolver();
            case self::COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY:
                return $this->getCategoryTaxonomyEnumStringScalarTypeResolver();
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
            case self::COMPONENT_FILTERINPUT_CATEGORY_IDS:
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            case self::COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return SchemaTypeModifiers::NONE;
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputDescription($component) : ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CATEGORY_IDS:
                return $this->__('Limit results to elements with the given ids', 'categories');
            case self::COMPONENT_FILTERINPUT_GENERIC_CATEGORY_TAXONOMY:
                return $this->__('Category taxonomy', 'categories');
            default:
                return null;
        }
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\ComponentProcessors\FormInputs;

use PoPCMSSchema\Tags\FilterInputs\TagIDsFilterInput;
use PoPCMSSchema\Tags\FilterInputs\TagSlugsFilterInput;
use PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\Taxonomies\FilterInputs\TaxonomyFilterInput;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public const COMPONENT_FILTERINPUT_TAG_SLUGS = 'filterinput-tag-slugs';
    public const COMPONENT_FILTERINPUT_TAG_IDS = 'filterinput-tag-ids';
    public const COMPONENT_FILTERINPUT_GENERIC_TAG_TAXONOMY = 'filterinput-generic-tag-taxonomy';
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Tags\FilterInputs\TagSlugsFilterInput|null
     */
    private $tagSlugsFilterInput;
    /**
     * @var \PoPCMSSchema\Tags\FilterInputs\TagIDsFilterInput|null
     */
    private $tagIDsFilterInput;
    /**
     * @var \PoPCMSSchema\Taxonomies\FilterInputs\TaxonomyFilterInput|null
     */
    private $taxonomyFilterInput;
    /**
     * @var \PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumStringScalarTypeResolver|null
     */
    private $tagTaxonomyEnumStringScalarTypeResolver;
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
     * @param \PoPCMSSchema\Tags\FilterInputs\TagSlugsFilterInput $tagSlugsFilterInput
     */
    public final function setTagSlugsFilterInput($tagSlugsFilterInput) : void
    {
        $this->tagSlugsFilterInput = $tagSlugsFilterInput;
    }
    protected final function getTagSlugsFilterInput() : TagSlugsFilterInput
    {
        /** @var TagSlugsFilterInput */
        return $this->tagSlugsFilterInput = $this->tagSlugsFilterInput ?? $this->instanceManager->getInstance(TagSlugsFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\FilterInputs\TagIDsFilterInput $tagIDsFilterInput
     */
    public final function setTagIDsFilterInput($tagIDsFilterInput) : void
    {
        $this->tagIDsFilterInput = $tagIDsFilterInput;
    }
    protected final function getTagIDsFilterInput() : TagIDsFilterInput
    {
        /** @var TagIDsFilterInput */
        return $this->tagIDsFilterInput = $this->tagIDsFilterInput ?? $this->instanceManager->getInstance(TagIDsFilterInput::class);
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
     * @param \PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumStringScalarTypeResolver $tagTaxonomyEnumStringScalarTypeResolver
     */
    public final function setTagTaxonomyEnumStringScalarTypeResolver($tagTaxonomyEnumStringScalarTypeResolver) : void
    {
        $this->tagTaxonomyEnumStringScalarTypeResolver = $tagTaxonomyEnumStringScalarTypeResolver;
    }
    protected final function getTagTaxonomyEnumStringScalarTypeResolver() : TagTaxonomyEnumStringScalarTypeResolver
    {
        /** @var TagTaxonomyEnumStringScalarTypeResolver */
        return $this->tagTaxonomyEnumStringScalarTypeResolver = $this->tagTaxonomyEnumStringScalarTypeResolver ?? $this->instanceManager->getInstance(TagTaxonomyEnumStringScalarTypeResolver::class);
    }
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUT_TAG_SLUGS, self::COMPONENT_FILTERINPUT_TAG_IDS, self::COMPONENT_FILTERINPUT_GENERIC_TAG_TAXONOMY);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInput($component) : ?FilterInputInterface
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_TAG_SLUGS:
                return $this->getTagSlugsFilterInput();
            case self::COMPONENT_FILTERINPUT_TAG_IDS:
                return $this->getTagIDsFilterInput();
            case self::COMPONENT_FILTERINPUT_GENERIC_TAG_TAXONOMY:
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
            case self::COMPONENT_FILTERINPUT_TAG_SLUGS:
            case self::COMPONENT_FILTERINPUT_TAG_IDS:
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
            case self::COMPONENT_FILTERINPUT_TAG_SLUGS:
                return 'tagSlugs';
            case self::COMPONENT_FILTERINPUT_TAG_IDS:
                return 'tagIDs';
            case self::COMPONENT_FILTERINPUT_GENERIC_TAG_TAXONOMY:
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
            case self::COMPONENT_FILTERINPUT_TAG_SLUGS:
                return $this->getStringScalarTypeResolver();
            case self::COMPONENT_FILTERINPUT_TAG_IDS:
                return $this->getIDScalarTypeResolver();
            case self::COMPONENT_FILTERINPUT_GENERIC_TAG_TAXONOMY:
                return $this->getTagTaxonomyEnumStringScalarTypeResolver();
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
            case self::COMPONENT_FILTERINPUT_TAG_SLUGS:
            case self::COMPONENT_FILTERINPUT_TAG_IDS:
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            case self::COMPONENT_FILTERINPUT_GENERIC_TAG_TAXONOMY:
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
            case self::COMPONENT_FILTERINPUT_TAG_SLUGS:
                return $this->__('Limit results to elements with the given tags', 'tags');
            case self::COMPONENT_FILTERINPUT_TAG_IDS:
                return $this->__('Limit results to elements with the given ids', 'tags');
            case self::COMPONENT_FILTERINPUT_GENERIC_TAG_TAXONOMY:
                return $this->__('Tag taxonomy', 'tags');
            default:
                return null;
        }
    }
}

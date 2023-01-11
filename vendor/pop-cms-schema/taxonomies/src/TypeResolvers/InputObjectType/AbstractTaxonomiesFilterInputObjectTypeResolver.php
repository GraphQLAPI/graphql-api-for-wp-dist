<?php

declare (strict_types=1);
namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SlugsFilterInput;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;
use PoPCMSSchema\Taxonomies\FilterInputs\HideEmptyFilterInput;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
abstract class AbstractTaxonomiesFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver|null
     */
    private $booleanScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDFilterInput|null
     */
    private $parentIDFilterInput;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput|null
     */
    private $searchFilterInput;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\SlugsFilterInput|null
     */
    private $slugsFilterInput;
    /**
     * @var \PoPCMSSchema\Taxonomies\FilterInputs\HideEmptyFilterInput|null
     */
    private $hideEmptyFilterInput;
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
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver $booleanScalarTypeResolver
     */
    public final function setBooleanScalarTypeResolver($booleanScalarTypeResolver) : void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    protected final function getBooleanScalarTypeResolver() : BooleanScalarTypeResolver
    {
        /** @var BooleanScalarTypeResolver */
        return $this->booleanScalarTypeResolver = $this->booleanScalarTypeResolver ?? $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDFilterInput $parentIDFilterInput
     */
    public final function setParentIDFilterInput($parentIDFilterInput) : void
    {
        $this->parentIDFilterInput = $parentIDFilterInput;
    }
    protected final function getParentIDFilterInput() : ParentIDFilterInput
    {
        /** @var ParentIDFilterInput */
        return $this->parentIDFilterInput = $this->parentIDFilterInput ?? $this->instanceManager->getInstance(ParentIDFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput $searchFilterInput
     */
    public final function setSearchFilterInput($searchFilterInput) : void
    {
        $this->searchFilterInput = $searchFilterInput;
    }
    protected final function getSearchFilterInput() : SearchFilterInput
    {
        /** @var SearchFilterInput */
        return $this->searchFilterInput = $this->searchFilterInput ?? $this->instanceManager->getInstance(SearchFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\SlugsFilterInput $slugsFilterInput
     */
    public final function setSlugsFilterInput($slugsFilterInput) : void
    {
        $this->slugsFilterInput = $slugsFilterInput;
    }
    protected final function getSlugsFilterInput() : SlugsFilterInput
    {
        /** @var SlugsFilterInput */
        return $this->slugsFilterInput = $this->slugsFilterInput ?? $this->instanceManager->getInstance(SlugsFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Taxonomies\FilterInputs\HideEmptyFilterInput $hideEmptyFilterInput
     */
    public final function setHideEmptyFilterInput($hideEmptyFilterInput) : void
    {
        $this->hideEmptyFilterInput = $hideEmptyFilterInput;
    }
    protected final function getHideEmptyFilterInput() : HideEmptyFilterInput
    {
        /** @var HideEmptyFilterInput */
        return $this->hideEmptyFilterInput = $this->hideEmptyFilterInput ?? $this->instanceManager->getInstance(HideEmptyFilterInput::class);
    }
    protected abstract function addParentIDInputField() : bool;
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return \array_merge(parent::getInputFieldNameTypeResolvers(), ['search' => $this->getStringScalarTypeResolver(), 'slugs' => $this->getStringScalarTypeResolver(), 'hideEmpty' => $this->getBooleanScalarTypeResolver()], $this->addParentIDInputField() ? ['parentID' => $this->getIDScalarTypeResolver()] : []);
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'search':
                return $this->__('Search for taxonomies containing the given string', 'taxonomies');
            case 'slugs':
                return $this->__('Search for taxonomies with the given slugs', 'taxonomies');
            case 'hideEmpty':
                return $this->__('Hide empty taxonomies terms?', 'taxonomies');
            case 'parentID':
                return $this->__('Limit results to taxonomies with the given parent ID. \'0\' means \'no parent\'', 'taxonomies');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldTypeModifiers($inputFieldName) : int
    {
        switch ($inputFieldName) {
            case 'slugs':
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            case 'hideEmpty':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getInputFieldTypeModifiers($inputFieldName);
        }
    }
    /**
     * @return mixed
     * @param string $inputFieldName
     */
    public function getInputFieldDefaultValue($inputFieldName)
    {
        switch ($inputFieldName) {
            case 'hideEmpty':
                return \false;
            default:
                return parent::getInputFieldDefaultValue($inputFieldName);
        }
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldName) : ?FilterInputInterface
    {
        switch ($inputFieldName) {
            case 'search':
                return $this->getSearchFilterInput();
            case 'slugs':
                return $this->getSlugsFilterInput();
            case 'hideEmpty':
                return $this->getHideEmptyFilterInput();
            case 'parentID':
                return $this->getParentIDFilterInput();
            default:
                return parent::getInputFieldFilterInput($inputFieldName);
        }
    }
}

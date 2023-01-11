<?php

declare (strict_types=1);
namespace PoPCMSSchema\Media\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Media\FilterInputs\MimeTypesFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\DateQueryInputObjectTypeResolver;
abstract class AbstractMediaItemsFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\DateQueryInputObjectTypeResolver|null
     */
    private $dateQueryInputObjectTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Media\FilterInputs\MimeTypesFilterInput|null
     */
    private $mimeTypesFilterInput;
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
     * @param \PoPCMSSchema\Media\FilterInputs\MimeTypesFilterInput $mimeTypesFilterInput
     */
    public final function setMimeTypesFilterInput($mimeTypesFilterInput) : void
    {
        $this->mimeTypesFilterInput = $mimeTypesFilterInput;
    }
    protected final function getMimeTypesFilterInput() : MimeTypesFilterInput
    {
        /** @var MimeTypesFilterInput */
        return $this->mimeTypesFilterInput = $this->mimeTypesFilterInput ?? $this->instanceManager->getInstance(MimeTypesFilterInput::class);
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
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return \array_merge(parent::getInputFieldNameTypeResolvers(), ['search' => $this->getStringScalarTypeResolver(), 'dateQuery' => $this->getDateQueryInputObjectTypeResolver(), 'mimeTypes' => $this->getStringScalarTypeResolver()]);
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'search':
                return $this->__('Search for comments containing the given string', 'comments');
            case 'dateQuery':
                return $this->__('Filter comments based on date', 'comments');
            case 'mimeTypes':
                return $this->__('Filter comments based on type', 'comments');
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
            case 'mimeTypes':
                return ['image'];
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
            case 'mimeTypes':
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
            case 'search':
                return $this->getSearchFilterInput();
            case 'mimeTypes':
                return $this->getMimeTypesFilterInput();
            default:
                return parent::getInputFieldFilterInput($inputFieldName);
        }
    }
}

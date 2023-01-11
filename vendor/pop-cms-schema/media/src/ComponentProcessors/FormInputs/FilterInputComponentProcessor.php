<?php

declare (strict_types=1);
namespace PoPCMSSchema\Media\ComponentProcessors\FormInputs;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Media\FilterInputs\MimeTypesFilterInput;
class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public const COMPONENT_FILTERINPUT_MIME_TYPES = 'filterinput-mime-types';
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Media\FilterInputs\MimeTypesFilterInput|null
     */
    private $mimeTypesFilterInput;
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
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUT_MIME_TYPES);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInput($component) : ?FilterInputInterface
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_MIME_TYPES:
                return $this->getMimeTypesFilterInput();
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
            case self::COMPONENT_FILTERINPUT_MIME_TYPES:
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
            case self::COMPONENT_FILTERINPUT_MIME_TYPES:
                return 'mimeTypes';
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
            case self::COMPONENT_FILTERINPUT_MIME_TYPES:
                return $this->getStringScalarTypeResolver();
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
            case self::COMPONENT_FILTERINPUT_MIME_TYPES:
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
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
            case self::COMPONENT_FILTERINPUT_MIME_TYPES:
                return $this->__('Limit results to elements with the given mime types', 'media');
            default:
                return null;
        }
    }
}

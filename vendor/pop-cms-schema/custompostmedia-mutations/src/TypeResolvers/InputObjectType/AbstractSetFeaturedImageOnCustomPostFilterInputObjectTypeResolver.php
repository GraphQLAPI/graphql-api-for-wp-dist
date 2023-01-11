<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\Constants\MutationInputProperties;
abstract class AbstractSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
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
    public function getTypeDescription() : ?string
    {
        return $this->__('Input to set the featured image on a custom post', 'custompostmedia-mutations');
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return \array_merge([MutationInputProperties::MEDIA_ITEM_ID => $this->getIDScalarTypeResolver()], $this->addCustomPostInputField() ? [MutationInputProperties::CUSTOMPOST_ID => $this->getIDScalarTypeResolver()] : []);
    }
    protected abstract function addCustomPostInputField() : bool;
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case MutationInputProperties::CUSTOMPOST_ID:
                return $this->__('The ID of the custom post', 'custompostmedia-mutations');
            case MutationInputProperties::MEDIA_ITEM_ID:
                return $this->__('The ID of the image to set as featured', 'custompostmedia-mutations');
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
            case MutationInputProperties::MEDIA_ITEM_ID:
            case MutationInputProperties::CUSTOMPOST_ID:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getInputFieldTypeModifiers($inputFieldName);
        }
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMutations\Constants\MutationInputProperties;
abstract class AbstractSetCategoriesOnCustomPostFilterInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver|null
     */
    private $booleanScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
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
        return $this->__('Input to set categories on a custom post', 'comment-mutations');
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return \array_merge($this->addCustomPostInputField() ? [MutationInputProperties::CUSTOMPOST_ID => $this->getIDScalarTypeResolver()] : [], [MutationInputProperties::CATEGORY_IDS => $this->getIDScalarTypeResolver(), MutationInputProperties::APPEND => $this->getBooleanScalarTypeResolver()]);
    }
    protected abstract function addCustomPostInputField() : bool;
    protected abstract function getEntityName() : string;
    protected abstract function getCategoryTypeResolver() : CategoryObjectTypeResolverInterface;
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case MutationInputProperties::CUSTOMPOST_ID:
                return \sprintf($this->__('The ID of the %s', 'custompost-category-mutations'), $this->getEntityName());
            case MutationInputProperties::CATEGORY_IDS:
                return \sprintf($this->__('The IDs of the categories to set, of type \'%s\'', 'custompost-category-mutations'), $this->getCategoryTypeResolver()->getMaybeNamespacedTypeName());
            case MutationInputProperties::APPEND:
                return $this->__('Append the categories to the existing ones?', 'custompost-category-mutations');
            default:
                return null;
        }
    }
    /**
     * @return mixed
     * @param string $inputFieldName
     */
    public function getInputFieldDefaultValue($inputFieldName)
    {
        switch ($inputFieldName) {
            case MutationInputProperties::APPEND:
                return \false;
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
            case MutationInputProperties::APPEND:
                return SchemaTypeModifiers::NON_NULLABLE;
            case MutationInputProperties::CUSTOMPOST_ID:
                return SchemaTypeModifiers::MANDATORY;
            case MutationInputProperties::CATEGORY_IDS:
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getInputFieldTypeModifiers($inputFieldName);
        }
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\Pages\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\ExcludeParentIDsFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDsFilterInput;
abstract class AbstractPagesFilterInputObjectTypeResolver extends AbstractCustomPostsFilterInputObjectTypeResolver implements \PoPCMSSchema\Pages\TypeResolvers\InputObjectType\PagesFilterInputObjectTypeResolverInterface
{
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDFilterInput|null
     */
    private $parentIDFilterInput;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDsFilterInput|null
     */
    private $parentIDsFilterInput;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\ExcludeParentIDsFilterInput|null
     */
    private $excludeParentIDsFilterInput;
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
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDsFilterInput $parentIDsFilterInput
     */
    public final function setParentIDsFilterInput($parentIDsFilterInput) : void
    {
        $this->parentIDsFilterInput = $parentIDsFilterInput;
    }
    protected final function getParentIDsFilterInput() : ParentIDsFilterInput
    {
        /** @var ParentIDsFilterInput */
        return $this->parentIDsFilterInput = $this->parentIDsFilterInput ?? $this->instanceManager->getInstance(ParentIDsFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\ExcludeParentIDsFilterInput $excludeParentIDsFilterInput
     */
    public final function setExcludeParentIDsFilterInput($excludeParentIDsFilterInput) : void
    {
        $this->excludeParentIDsFilterInput = $excludeParentIDsFilterInput;
    }
    protected final function getExcludeParentIDsFilterInput() : ExcludeParentIDsFilterInput
    {
        /** @var ExcludeParentIDsFilterInput */
        return $this->excludeParentIDsFilterInput = $this->excludeParentIDsFilterInput ?? $this->instanceManager->getInstance(ExcludeParentIDsFilterInput::class);
    }
    protected abstract function addParentInputFields() : bool;
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return \array_merge(parent::getInputFieldNameTypeResolvers(), $this->addParentInputFields() ? ['parentID' => $this->getIDScalarTypeResolver(), 'parentIDs' => $this->getIDScalarTypeResolver(), 'excludeParentIDs' => $this->getIDScalarTypeResolver()] : []);
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'parentID':
                return $this->__('Filter pages with the given parent IDs. \'0\' means \'no parent\'', 'pages');
            case 'parentIDs':
                return $this->__('Filter pages with the given parent ID. \'0\' means \'no parent\'', 'pages');
            case 'excludeParentIDs':
                return $this->__('Exclude pages with the given parent IDs', 'pages');
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
            case 'parentIDs':
            case 'excludeParentIDs':
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
            case 'parentID':
                return $this->getParentIDFilterInput();
            case 'parentIDs':
                return $this->getParentIDsFilterInput();
            case 'excludeParentIDs':
                return $this->getExcludeParentIDsFilterInput();
            default:
                return parent::getInputFieldFilterInput($inputFieldName);
        }
    }
}

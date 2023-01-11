<?php

declare (strict_types=1);
namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\IncludeFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SlugFilterInput;
abstract class AbstractTaxonomyByInputObjectTypeResolver extends AbstractOneofQueryableInputObjectTypeResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\IncludeFilterInput|null
     */
    private $includeFilterInput;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\SlugFilterInput|null
     */
    private $slugFilterInput;
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
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\IncludeFilterInput $includeFilterInput
     */
    public final function setIncludeFilterInput($includeFilterInput) : void
    {
        $this->includeFilterInput = $includeFilterInput;
    }
    protected final function getIncludeFilterInput() : IncludeFilterInput
    {
        /** @var IncludeFilterInput */
        return $this->includeFilterInput = $this->includeFilterInput ?? $this->instanceManager->getInstance(IncludeFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\SlugFilterInput $slugFilterInput
     */
    public final function setSlugFilterInput($slugFilterInput) : void
    {
        $this->slugFilterInput = $slugFilterInput;
    }
    protected final function getSlugFilterInput() : SlugFilterInput
    {
        /** @var SlugFilterInput */
        return $this->slugFilterInput = $this->slugFilterInput ?? $this->instanceManager->getInstance(SlugFilterInput::class);
    }
    public function getTypeDescription() : ?string
    {
        return \sprintf($this->__('Oneof input to specify the property and data to fetch %s', 'customposts'), $this->getTypeDescriptionTaxonomyEntity());
    }
    protected function getTypeDescriptionTaxonomyEntity() : string
    {
        return $this->__('a taxonomy', 'customposts');
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return ['id' => $this->getIDScalarTypeResolver(), 'slug' => $this->getStringScalarTypeResolver()];
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'id':
                return $this->__('Query by taxonomy ID', 'taxonomies');
            case 'slug':
                return $this->__('Query by taxonomy slug', 'taxonomies');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldName) : ?FilterInputInterface
    {
        switch ($inputFieldName) {
            case 'id':
                return $this->getIncludeFilterInput();
            case 'slug':
                return $this->getSlugFilterInput();
            default:
                return parent::getInputFieldFilterInput($inputFieldName);
        }
    }
}

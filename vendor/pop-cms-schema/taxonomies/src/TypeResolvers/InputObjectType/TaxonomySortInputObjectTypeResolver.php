<?php

declare (strict_types=1);
namespace PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\Taxonomies\Constants\TaxonomyOrderBy;
use PoPCMSSchema\Taxonomies\TypeResolvers\EnumType\TaxonomyOrderByEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;
class TaxonomySortInputObjectTypeResolver extends SortInputObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\Taxonomies\TypeResolvers\EnumType\TaxonomyOrderByEnumTypeResolver|null
     */
    private $taxonomySortByEnumTypeResolver;
    /**
     * @param \PoPCMSSchema\Taxonomies\TypeResolvers\EnumType\TaxonomyOrderByEnumTypeResolver $taxonomySortByEnumTypeResolver
     */
    public final function setTaxonomyOrderByEnumTypeResolver($taxonomySortByEnumTypeResolver) : void
    {
        $this->taxonomySortByEnumTypeResolver = $taxonomySortByEnumTypeResolver;
    }
    protected final function getTaxonomyOrderByEnumTypeResolver() : TaxonomyOrderByEnumTypeResolver
    {
        /** @var TaxonomyOrderByEnumTypeResolver */
        return $this->taxonomySortByEnumTypeResolver = $this->taxonomySortByEnumTypeResolver ?? $this->instanceManager->getInstance(TaxonomyOrderByEnumTypeResolver::class);
    }
    public function getTypeName() : string
    {
        return 'TaxonomySortInput';
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return \array_merge(parent::getInputFieldNameTypeResolvers(), ['by' => $this->getTaxonomyOrderByEnumTypeResolver()]);
    }
    /**
     * @return mixed
     * @param string $inputFieldName
     */
    public function getInputFieldDefaultValue($inputFieldName)
    {
        switch ($inputFieldName) {
            case 'by':
                return TaxonomyOrderBy::NAME;
            default:
                return parent::getInputFieldDefaultValue($inputFieldName);
        }
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\TypeResolvers\InputObjectType;

use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\AbstractTaxonomyByInputObjectTypeResolver;
abstract class AbstractCategoryByInputObjectTypeResolver extends AbstractTaxonomyByInputObjectTypeResolver
{
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'id':
                return $this->__('Query by category ID', 'categories');
            case 'slug':
                return $this->__('Query by category slug', 'categories');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
    protected function getTypeDescriptionTaxonomyEntity() : string
    {
        return $this->__('a category', 'categories');
    }
}

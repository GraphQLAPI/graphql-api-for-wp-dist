<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\TypeResolvers\InputObjectType;

use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\AbstractTaxonomyByInputObjectTypeResolver;
abstract class AbstractTagByInputObjectTypeResolver extends AbstractTaxonomyByInputObjectTypeResolver
{
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'id':
                return $this->__('Query by tag ID', 'tags');
            case 'slug':
                return $this->__('Query by tag slug', 'tags');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
    protected function getTypeDescriptionTaxonomyEntity() : string
    {
        return $this->__('a tag', 'tags');
    }
}

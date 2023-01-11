<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\FieldResolvers\InterfaceType;

use PoPCMSSchema\Categories\TypeResolvers\InterfaceType\CategoryInterfaceTypeResolver;
use PoPCMSSchema\Taxonomies\FieldResolvers\InterfaceType\AbstractIsTaxonomyInterfaceTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
class CategoryInterfaceTypeFieldResolver extends AbstractIsTaxonomyInterfaceTypeFieldResolver
{
    /**
     * @return array<class-string<InterfaceTypeResolverInterface>>
     */
    public function getInterfaceTypeResolverClassesToAttachTo() : array
    {
        return [CategoryInterfaceTypeResolver::class];
    }
    /**
     * @param string $fieldName
     */
    public function getFieldDescription($fieldName) : ?string
    {
        switch ($fieldName) {
            case 'name':
                return $this->__('Category', 'categories');
            case 'description':
                return $this->__('Category description', 'categories');
            case 'count':
                return $this->__('Number of custom posts containing this category', 'categories');
            default:
                return parent::getFieldDescription($fieldName);
        }
    }
}

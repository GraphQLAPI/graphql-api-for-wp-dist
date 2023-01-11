<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\TypeResolvers\ObjectType;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeResolvers\ObjectType\AbstractTaxonomyObjectTypeResolver;
abstract class AbstractCategoryObjectTypeResolver extends AbstractTaxonomyObjectTypeResolver implements \PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface
{
    public abstract function getCategoryTypeAPI() : CategoryTypeAPIInterface;
    public function getTypeDescription() : ?string
    {
        return $this->__('Representation of a category, added to a custom post', 'categories');
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        $categoryTypeAPI = $this->getCategoryTypeAPI();
        $category = $object;
        return $categoryTypeAPI->getCategoryID($category);
    }
}

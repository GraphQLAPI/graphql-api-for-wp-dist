<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\Registries;

use PoPCMSSchema\Categories\ObjectTypeResolverPickers\CategoryObjectTypeResolverPickerInterface;
class CategoryObjectTypeResolverPickerRegistry implements \PoPCMSSchema\Categories\Registries\CategoryObjectTypeResolverPickerRegistryInterface
{
    /**
     * @var CategoryObjectTypeResolverPickerInterface[]
     */
    protected $categoryObjectTypeResolverPickers = [];
    /**
     * @param \PoPCMSSchema\Categories\ObjectTypeResolverPickers\CategoryObjectTypeResolverPickerInterface $categoryObjectTypeResolverPicker
     */
    public function addCategoryObjectTypeResolverPicker($categoryObjectTypeResolverPicker) : void
    {
        $this->categoryObjectTypeResolverPickers[] = $categoryObjectTypeResolverPicker;
    }
    /**
     * @return CategoryObjectTypeResolverPickerInterface[]
     */
    public function getCategoryObjectTypeResolverPickers() : array
    {
        return $this->categoryObjectTypeResolverPickers;
    }
}

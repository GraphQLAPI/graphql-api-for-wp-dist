<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\Registries;

use PoPCMSSchema\Categories\ObjectTypeResolverPickers\CategoryObjectTypeResolverPickerInterface;
interface CategoryObjectTypeResolverPickerRegistryInterface
{
    /**
     * @param \PoPCMSSchema\Categories\ObjectTypeResolverPickers\CategoryObjectTypeResolverPickerInterface $customPostObjectTypeResolverPicker
     */
    public function addCategoryObjectTypeResolverPicker($customPostObjectTypeResolverPicker) : void;
    /**
     * @return CategoryObjectTypeResolverPickerInterface[]
     */
    public function getCategoryObjectTypeResolverPickers() : array;
}

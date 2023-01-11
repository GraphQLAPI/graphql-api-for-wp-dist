<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\Registries;

use PoPCMSSchema\Tags\ObjectTypeResolverPickers\TagObjectTypeResolverPickerInterface;
interface TagObjectTypeResolverPickerRegistryInterface
{
    /**
     * @param \PoPCMSSchema\Tags\ObjectTypeResolverPickers\TagObjectTypeResolverPickerInterface $customPostObjectTypeResolverPicker
     */
    public function addTagObjectTypeResolverPicker($customPostObjectTypeResolverPicker) : void;
    /**
     * @return TagObjectTypeResolverPickerInterface[]
     */
    public function getTagObjectTypeResolverPickers() : array;
}

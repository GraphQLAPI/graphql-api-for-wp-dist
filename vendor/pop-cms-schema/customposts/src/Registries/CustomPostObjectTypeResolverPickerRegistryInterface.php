<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\Registries;

use PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;
interface CustomPostObjectTypeResolverPickerRegistryInterface
{
    /**
     * @param \PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface $customPostObjectTypeResolverPicker
     */
    public function addCustomPostObjectTypeResolverPicker($customPostObjectTypeResolverPicker) : void;
    /**
     * @return CustomPostObjectTypeResolverPickerInterface[]
     */
    public function getCustomPostObjectTypeResolverPickers() : array;
}

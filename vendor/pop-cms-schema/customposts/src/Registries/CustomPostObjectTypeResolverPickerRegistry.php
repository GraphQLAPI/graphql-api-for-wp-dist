<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\Registries;

use PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;
class CustomPostObjectTypeResolverPickerRegistry implements \PoPCMSSchema\CustomPosts\Registries\CustomPostObjectTypeResolverPickerRegistryInterface
{
    /**
     * @var CustomPostObjectTypeResolverPickerInterface[]
     */
    protected $customPostObjectTypeResolverPickers = [];
    /**
     * @param \PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface $customPostObjectTypeResolverPicker
     */
    public function addCustomPostObjectTypeResolverPicker($customPostObjectTypeResolverPicker) : void
    {
        $this->customPostObjectTypeResolverPickers[] = $customPostObjectTypeResolverPicker;
    }
    /**
     * @return CustomPostObjectTypeResolverPickerInterface[]
     */
    public function getCustomPostObjectTypeResolverPickers() : array
    {
        return $this->customPostObjectTypeResolverPickers;
    }
}

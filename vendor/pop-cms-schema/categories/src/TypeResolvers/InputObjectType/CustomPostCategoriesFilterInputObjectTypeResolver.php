<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\TypeResolvers\InputObjectType;

class CustomPostCategoriesFilterInputObjectTypeResolver extends \PoPCMSSchema\Categories\TypeResolvers\InputObjectType\AbstractCategoriesFilterInputObjectTypeResolver
{
    public function getTypeName() : string
    {
        return 'CustomPostCategoriesFilterInput';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Input to filter categories from a custom post', 'categories');
    }
}

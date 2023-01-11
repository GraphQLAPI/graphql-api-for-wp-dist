<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType;

class RootCustomPostsFilterInputObjectTypeResolver extends \PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver implements \PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostsFilterInputObjectTypeResolverInterface
{
    public function getTypeName() : string
    {
        return 'RootCustomPostsFilterInput';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Input to filter custom posts', 'customposts');
    }
    protected function addCustomPostInputFields() : bool
    {
        return \true;
    }
}

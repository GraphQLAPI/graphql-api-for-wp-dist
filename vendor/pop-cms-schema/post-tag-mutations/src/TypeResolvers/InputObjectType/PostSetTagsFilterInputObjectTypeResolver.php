<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType;

class PostSetTagsFilterInputObjectTypeResolver extends \PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnPostFilterInputObjectTypeResolver
{
    public function getTypeName() : string
    {
        return 'PostSetTagsFilterInput';
    }
    protected function addCustomPostInputField() : bool
    {
        return \false;
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType;

class RootSetTagsOnCustomPostFilterInputObjectTypeResolver extends \PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnPostFilterInputObjectTypeResolver
{
    public function getTypeName() : string
    {
        return 'RootSetTagsOnCustomPostFilterInput';
    }
    protected function addCustomPostInputField() : bool
    {
        return \true;
    }
}

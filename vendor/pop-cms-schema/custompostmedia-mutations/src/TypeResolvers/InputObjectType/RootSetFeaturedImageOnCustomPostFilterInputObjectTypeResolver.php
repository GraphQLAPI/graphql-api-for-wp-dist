<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType;

class RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver extends \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\AbstractSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver
{
    public function getTypeName() : string
    {
        return 'RootSetFeaturedImageOnCustomPostFilterInput';
    }
    protected function addCustomPostInputField() : bool
    {
        return \true;
    }
}

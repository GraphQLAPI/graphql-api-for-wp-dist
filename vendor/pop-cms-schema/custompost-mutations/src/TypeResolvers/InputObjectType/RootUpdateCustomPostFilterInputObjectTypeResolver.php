<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class RootUpdateCustomPostFilterInputObjectTypeResolver extends \PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractCreateOrUpdateCustomPostFilterInputObjectTypeResolver implements \PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\UpdateCustomPostFilterInputObjectTypeResolverInterface
{
    public function getTypeName() : string
    {
        return 'RootUpdateCustomPostFilterInput';
    }
    protected function addCustomPostInputField() : bool
    {
        return \true;
    }
}

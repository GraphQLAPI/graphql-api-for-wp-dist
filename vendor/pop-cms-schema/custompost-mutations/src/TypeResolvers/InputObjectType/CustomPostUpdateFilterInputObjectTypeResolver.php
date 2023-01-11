<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class CustomPostUpdateFilterInputObjectTypeResolver extends \PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractCreateOrUpdateCustomPostFilterInputObjectTypeResolver implements \PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\UpdateCustomPostFilterInputObjectTypeResolverInterface
{
    public function getTypeName() : string
    {
        return 'CustomPostUpdateFilterInput';
    }
    protected function addCustomPostInputField() : bool
    {
        return \false;
    }
}

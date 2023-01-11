<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

class RootCreateCustomPostFilterInputObjectTypeResolver extends \PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractCreateOrUpdateCustomPostFilterInputObjectTypeResolver implements \PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\CreateCustomPostFilterInputObjectTypeResolverInterface
{
    public function getTypeName() : string
    {
        return 'RootCreateCustomPostFilterInput';
    }
    protected function addCustomPostInputField() : bool
    {
        return \false;
    }
}

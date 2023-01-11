<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\RootUpdateCustomPostFilterInputObjectTypeResolver;
class RootUpdatePostFilterInputObjectTypeResolver extends RootUpdateCustomPostFilterInputObjectTypeResolver implements \PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\UpdatePostFilterInputObjectTypeResolverInterface
{
    public function getTypeName() : string
    {
        return 'RootUpdatePostFilterInput';
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\CustomPostUpdateFilterInputObjectTypeResolver;
class PostUpdateFilterInputObjectTypeResolver extends CustomPostUpdateFilterInputObjectTypeResolver implements \PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\UpdatePostFilterInputObjectTypeResolverInterface
{
    public function getTypeName() : string
    {
        return 'PostUpdateFilterInput';
    }
}

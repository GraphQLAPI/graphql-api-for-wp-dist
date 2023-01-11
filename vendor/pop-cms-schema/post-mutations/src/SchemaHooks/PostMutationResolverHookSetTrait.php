<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\CreatePostFilterInputObjectTypeResolverInterface;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\UpdatePostFilterInputObjectTypeResolverInterface;
trait PostMutationResolverHookSetTrait
{
    /**
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    protected function isInputObjectTypeResolver($inputObjectTypeResolver) : bool
    {
        return $inputObjectTypeResolver instanceof CreatePostFilterInputObjectTypeResolverInterface || $inputObjectTypeResolver instanceof UpdatePostFilterInputObjectTypeResolverInterface;
    }
}

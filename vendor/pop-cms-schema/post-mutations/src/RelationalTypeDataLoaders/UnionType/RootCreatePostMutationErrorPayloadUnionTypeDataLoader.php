<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootCreatePostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class RootCreatePostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootCreatePostMutationErrorPayloadUnionTypeResolver|null
     */
    private $rootCreatePostMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootCreatePostMutationErrorPayloadUnionTypeResolver $rootCreatePostMutationErrorPayloadUnionTypeResolver
     */
    public final function setRootCreatePostMutationErrorPayloadUnionTypeResolver($rootCreatePostMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->rootCreatePostMutationErrorPayloadUnionTypeResolver = $rootCreatePostMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getRootCreatePostMutationErrorPayloadUnionTypeResolver() : RootCreatePostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootCreatePostMutationErrorPayloadUnionTypeResolver */
        return $this->rootCreatePostMutationErrorPayloadUnionTypeResolver = $this->rootCreatePostMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(RootCreatePostMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getRootCreatePostMutationErrorPayloadUnionTypeResolver();
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver|null
     */
    private $rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver $rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver
     */
    public final function setRootSetTagsOnPostMutationErrorPayloadUnionTypeResolver($rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver = $rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getRootSetTagsOnPostMutationErrorPayloadUnionTypeResolver() : RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver */
        return $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver = $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getRootSetTagsOnPostMutationErrorPayloadUnionTypeResolver();
    }
}

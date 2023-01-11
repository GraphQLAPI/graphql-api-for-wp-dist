<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootUpdatePostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class RootUpdatePostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootUpdatePostMutationErrorPayloadUnionTypeResolver|null
     */
    private $rootUpdatePostMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootUpdatePostMutationErrorPayloadUnionTypeResolver $rootUpdatePostMutationErrorPayloadUnionTypeResolver
     */
    public final function setRootUpdatePostMutationErrorPayloadUnionTypeResolver($rootUpdatePostMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->rootUpdatePostMutationErrorPayloadUnionTypeResolver = $rootUpdatePostMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getRootUpdatePostMutationErrorPayloadUnionTypeResolver() : RootUpdatePostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootUpdatePostMutationErrorPayloadUnionTypeResolver */
        return $this->rootUpdatePostMutationErrorPayloadUnionTypeResolver = $this->rootUpdatePostMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(RootUpdatePostMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getRootUpdatePostMutationErrorPayloadUnionTypeResolver();
    }
}

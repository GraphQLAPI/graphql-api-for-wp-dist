<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver|null
     */
    private $rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver $rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver
     */
    public final function setRootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver($rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver = $rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getRootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver() : RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver */
        return $this->rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver = $this->rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getRootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver|null
     */
    private $rootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver $rootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver
     */
    public final function setRootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver($rootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->rootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver = $rootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getRootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver() : RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver */
        return $this->rootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver = $this->rootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getRootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}

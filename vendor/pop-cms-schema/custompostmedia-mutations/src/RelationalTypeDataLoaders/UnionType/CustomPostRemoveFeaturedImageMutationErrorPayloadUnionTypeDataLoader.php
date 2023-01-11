<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver|null
     */
    private $customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver $customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver
     */
    public final function setCustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver($customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver = $customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getCustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver() : CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver
    {
        /** @var CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver */
        return $this->customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver = $this->customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getCustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver();
    }
}

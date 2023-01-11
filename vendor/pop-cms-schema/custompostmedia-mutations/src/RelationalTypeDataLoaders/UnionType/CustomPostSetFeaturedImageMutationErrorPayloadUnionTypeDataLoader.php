<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver|null
     */
    private $customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver $customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver
     */
    public final function setCustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver($customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver = $customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getCustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver() : CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver
    {
        /** @var CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver */
        return $this->customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver = $this->customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getCustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver();
    }
}

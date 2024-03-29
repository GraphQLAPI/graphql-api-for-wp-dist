<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\UnionType\CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver extends \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\AbstractCustomPostMediaMutationErrorPayloadUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\UnionType\CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader|null
     */
    private $customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader;
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\UnionType\CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader $customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader
     */
    public final function setCustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader($customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader) : void
    {
        $this->customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader = $customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader;
    }
    protected final function getCustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader() : CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader */
        return $this->customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader = $this->customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader ?? $this->instanceManager->getInstance(CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'CustomPostRemoveFeaturedImageMutationErrorPayloadUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'Error Payload\' types when removing a featured from a custom post (using nested mutations)', 'custompostmedia-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeDataLoader();
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\ObjectType\FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\ObjectType\FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader|null
     */
    private $featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader;
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\ObjectType\FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader $featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader
     */
    public final function setFeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader($featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader) : void
    {
        $this->featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader = $featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader;
    }
    protected final function getFeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader() : FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader
    {
        /** @var FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader */
        return $this->featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader = $this->featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader ?? $this->instanceManager->getInstance(FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'FeaturedImageIsNotSupportedByCustomPostTypeErrorPayload';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Error payload for: "Setting the featured image is not supported by the custom post type"', 'custompostmedia-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getFeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader();
    }
}

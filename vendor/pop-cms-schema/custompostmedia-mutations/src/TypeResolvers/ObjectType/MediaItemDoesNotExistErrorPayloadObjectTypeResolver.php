<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\ObjectType\MediaItemDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class MediaItemDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\ObjectType\MediaItemDoesNotExistErrorPayloadObjectTypeDataLoader|null
     */
    private $customPostDoesNotExistErrorPayloadObjectTypeDataLoader;
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\ObjectType\MediaItemDoesNotExistErrorPayloadObjectTypeDataLoader $customPostDoesNotExistErrorPayloadObjectTypeDataLoader
     */
    public final function setMediaItemDoesNotExistErrorPayloadObjectTypeDataLoader($customPostDoesNotExistErrorPayloadObjectTypeDataLoader) : void
    {
        $this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader = $customPostDoesNotExistErrorPayloadObjectTypeDataLoader;
    }
    protected final function getMediaItemDoesNotExistErrorPayloadObjectTypeDataLoader() : MediaItemDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        /** @var MediaItemDoesNotExistErrorPayloadObjectTypeDataLoader */
        return $this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader = $this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader ?? $this->instanceManager->getInstance(MediaItemDoesNotExistErrorPayloadObjectTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'MediaItemDoesNotExistErrorPayload';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Error payload for: "The requested media item does not exist"', 'custompostmedia-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getMediaItemDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}

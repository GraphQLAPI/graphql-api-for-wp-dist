<?php

declare (strict_types=1);
namespace PoPCMSSchema\Media\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPCMSSchema\Media\RelationalTypeDataLoaders\ObjectType\MediaTypeDataLoader;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
class MediaObjectTypeResolver extends AbstractObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface|null
     */
    private $mediaTypeAPI;
    /**
     * @var \PoPCMSSchema\Media\RelationalTypeDataLoaders\ObjectType\MediaTypeDataLoader|null
     */
    private $mediaTypeDataLoader;
    /**
     * @param \PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface $mediaTypeAPI
     */
    public final function setMediaTypeAPI($mediaTypeAPI) : void
    {
        $this->mediaTypeAPI = $mediaTypeAPI;
    }
    protected final function getMediaTypeAPI() : MediaTypeAPIInterface
    {
        /** @var MediaTypeAPIInterface */
        return $this->mediaTypeAPI = $this->mediaTypeAPI ?? $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Media\RelationalTypeDataLoaders\ObjectType\MediaTypeDataLoader $mediaTypeDataLoader
     */
    public final function setMediaTypeDataLoader($mediaTypeDataLoader) : void
    {
        $this->mediaTypeDataLoader = $mediaTypeDataLoader;
    }
    protected final function getMediaTypeDataLoader() : MediaTypeDataLoader
    {
        /** @var MediaTypeDataLoader */
        return $this->mediaTypeDataLoader = $this->mediaTypeDataLoader ?? $this->instanceManager->getInstance(MediaTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'Media';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Media elements (such as images, videos, etc), attached to a post or independent', 'media');
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        $media = $object;
        return $this->getMediaTypeAPI()->getMediaItemID($media);
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getMediaTypeDataLoader();
    }
}

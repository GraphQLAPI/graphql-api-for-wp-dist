<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMediaMutations\ObjectModels\MediaItemDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\MediaItemDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractMediaItemDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\MediaItemDoesNotExistErrorPayloadObjectTypeResolver|null
     */
    private $mediaItemDoesNotExistErrorPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\MediaItemDoesNotExistErrorPayloadObjectTypeResolver $mediaItemDoesNotExistErrorPayloadObjectTypeResolver
     */
    public final function setMediaItemDoesNotExistErrorPayloadObjectTypeResolver($mediaItemDoesNotExistErrorPayloadObjectTypeResolver) : void
    {
        $this->mediaItemDoesNotExistErrorPayloadObjectTypeResolver = $mediaItemDoesNotExistErrorPayloadObjectTypeResolver;
    }
    protected final function getMediaItemDoesNotExistErrorPayloadObjectTypeResolver() : MediaItemDoesNotExistErrorPayloadObjectTypeResolver
    {
        /** @var MediaItemDoesNotExistErrorPayloadObjectTypeResolver */
        return $this->mediaItemDoesNotExistErrorPayloadObjectTypeResolver = $this->mediaItemDoesNotExistErrorPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(MediaItemDoesNotExistErrorPayloadObjectTypeResolver::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getMediaItemDoesNotExistErrorPayloadObjectTypeResolver();
    }
    protected function getTargetObjectClass() : string
    {
        return MediaItemDoesNotExistErrorPayload::class;
    }
}

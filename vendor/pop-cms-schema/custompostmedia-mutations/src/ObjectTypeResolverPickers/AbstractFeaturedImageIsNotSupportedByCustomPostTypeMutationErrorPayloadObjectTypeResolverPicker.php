<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMediaMutations\ObjectModels\FeaturedImageIsNotSupportedByCustomPostTypeErrorPayload;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractFeaturedImageIsNotSupportedByCustomPostTypeMutationErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver|null
     */
    private $featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver $featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver
     */
    public final function setFeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver($featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver) : void
    {
        $this->featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver = $featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver;
    }
    protected final function getFeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver() : FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver
    {
        /** @var FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver */
        return $this->featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver = $this->featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getFeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver();
    }
    protected function getTargetObjectClass() : string
    {
        return FeaturedImageIsNotSupportedByCustomPostTypeErrorPayload::class;
    }
}

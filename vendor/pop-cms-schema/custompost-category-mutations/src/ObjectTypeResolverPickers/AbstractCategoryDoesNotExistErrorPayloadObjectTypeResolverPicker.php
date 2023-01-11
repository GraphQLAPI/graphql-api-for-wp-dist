<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostCategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostCategoryMutations\ObjectModels\CategoryDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType\CategoryDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractCategoryDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    /**
     * @var \PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType\CategoryDoesNotExistErrorPayloadObjectTypeResolver|null
     */
    private $mediaItemDoesNotExistErrorPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType\CategoryDoesNotExistErrorPayloadObjectTypeResolver $mediaItemDoesNotExistErrorPayloadObjectTypeResolver
     */
    public final function setCategoryDoesNotExistErrorPayloadObjectTypeResolver($mediaItemDoesNotExistErrorPayloadObjectTypeResolver) : void
    {
        $this->mediaItemDoesNotExistErrorPayloadObjectTypeResolver = $mediaItemDoesNotExistErrorPayloadObjectTypeResolver;
    }
    protected final function getCategoryDoesNotExistErrorPayloadObjectTypeResolver() : CategoryDoesNotExistErrorPayloadObjectTypeResolver
    {
        /** @var CategoryDoesNotExistErrorPayloadObjectTypeResolver */
        return $this->mediaItemDoesNotExistErrorPayloadObjectTypeResolver = $this->mediaItemDoesNotExistErrorPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(CategoryDoesNotExistErrorPayloadObjectTypeResolver::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getCategoryDoesNotExistErrorPayloadObjectTypeResolver();
    }
    protected function getTargetObjectClass() : string
    {
        return CategoryDoesNotExistErrorPayload::class;
    }
}

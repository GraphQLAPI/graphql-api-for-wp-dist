<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMutations\RelationalTypeDataLoaders\ObjectType\CategoryDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class CategoryDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\CustomPostCategoryMutations\RelationalTypeDataLoaders\ObjectType\CategoryDoesNotExistErrorPayloadObjectTypeDataLoader|null
     */
    private $customPostDoesNotExistErrorPayloadObjectTypeDataLoader;
    /**
     * @param \PoPCMSSchema\CustomPostCategoryMutations\RelationalTypeDataLoaders\ObjectType\CategoryDoesNotExistErrorPayloadObjectTypeDataLoader $customPostDoesNotExistErrorPayloadObjectTypeDataLoader
     */
    public final function setCategoryDoesNotExistErrorPayloadObjectTypeDataLoader($customPostDoesNotExistErrorPayloadObjectTypeDataLoader) : void
    {
        $this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader = $customPostDoesNotExistErrorPayloadObjectTypeDataLoader;
    }
    protected final function getCategoryDoesNotExistErrorPayloadObjectTypeDataLoader() : CategoryDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        /** @var CategoryDoesNotExistErrorPayloadObjectTypeDataLoader */
        return $this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader = $this->customPostDoesNotExistErrorPayloadObjectTypeDataLoader ?? $this->instanceManager->getInstance(CategoryDoesNotExistErrorPayloadObjectTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'CategoryDoesNotExistErrorPayload';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Error payload for: "The requested category does not exist"', 'custompost-category-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getCategoryDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType;

use PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType\PostSetCategoriesMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class PostSetCategoriesMutationErrorPayloadUnionTypeResolver extends \PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\AbstractPostCategoriesMutationErrorPayloadUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType\PostSetCategoriesMutationErrorPayloadUnionTypeDataLoader|null
     */
    private $postSetCategoriesMutationErrorPayloadUnionTypeDataLoader;
    /**
     * @param \PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType\PostSetCategoriesMutationErrorPayloadUnionTypeDataLoader $postSetCategoriesMutationErrorPayloadUnionTypeDataLoader
     */
    public final function setPostSetCategoriesMutationErrorPayloadUnionTypeDataLoader($postSetCategoriesMutationErrorPayloadUnionTypeDataLoader) : void
    {
        $this->postSetCategoriesMutationErrorPayloadUnionTypeDataLoader = $postSetCategoriesMutationErrorPayloadUnionTypeDataLoader;
    }
    protected final function getPostSetCategoriesMutationErrorPayloadUnionTypeDataLoader() : PostSetCategoriesMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var PostSetCategoriesMutationErrorPayloadUnionTypeDataLoader */
        return $this->postSetCategoriesMutationErrorPayloadUnionTypeDataLoader = $this->postSetCategoriesMutationErrorPayloadUnionTypeDataLoader ?? $this->instanceManager->getInstance(PostSetCategoriesMutationErrorPayloadUnionTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'PostSetCategoriesMutationErrorPayloadUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting categories on a custom post (using nested mutations)', 'postcategory-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getPostSetCategoriesMutationErrorPayloadUnionTypeDataLoader();
    }
}

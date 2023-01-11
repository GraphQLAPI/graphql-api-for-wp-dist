<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType;

use PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType\RootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver extends \PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\AbstractPostCategoriesMutationErrorPayloadUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType\RootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader|null
     */
    private $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader;
    /**
     * @param \PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType\RootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader
     */
    public final function setRootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader($rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader) : void
    {
        $this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader = $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader;
    }
    protected final function getRootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader() : RootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var RootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader */
        return $this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader = $this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader ?? $this->instanceManager->getInstance(RootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'RootSetCategoriesOnPostMutationErrorPayloadUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting categories on a custom post', 'postcategory-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getRootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader();
    }
}

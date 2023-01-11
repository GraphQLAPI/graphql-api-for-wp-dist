<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType;

use PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType\PostSetTagsMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class PostSetTagsMutationErrorPayloadUnionTypeResolver extends \PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\AbstractPostTagsMutationErrorPayloadUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType\PostSetTagsMutationErrorPayloadUnionTypeDataLoader|null
     */
    private $postSetTagsMutationErrorPayloadUnionTypeDataLoader;
    /**
     * @param \PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType\PostSetTagsMutationErrorPayloadUnionTypeDataLoader $postSetTagsMutationErrorPayloadUnionTypeDataLoader
     */
    public final function setPostSetTagsMutationErrorPayloadUnionTypeDataLoader($postSetTagsMutationErrorPayloadUnionTypeDataLoader) : void
    {
        $this->postSetTagsMutationErrorPayloadUnionTypeDataLoader = $postSetTagsMutationErrorPayloadUnionTypeDataLoader;
    }
    protected final function getPostSetTagsMutationErrorPayloadUnionTypeDataLoader() : PostSetTagsMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var PostSetTagsMutationErrorPayloadUnionTypeDataLoader */
        return $this->postSetTagsMutationErrorPayloadUnionTypeDataLoader = $this->postSetTagsMutationErrorPayloadUnionTypeDataLoader ?? $this->instanceManager->getInstance(PostSetTagsMutationErrorPayloadUnionTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'PostSetTagsMutationErrorPayloadUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting tags on a custom post (using nested mutations)', 'posttag-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getPostSetTagsMutationErrorPayloadUnionTypeDataLoader();
    }
}

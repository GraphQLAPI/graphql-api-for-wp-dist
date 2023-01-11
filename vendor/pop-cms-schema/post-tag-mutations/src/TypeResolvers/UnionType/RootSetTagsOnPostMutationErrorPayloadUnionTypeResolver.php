<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType;

use PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType\RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver extends \PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\AbstractPostTagsMutationErrorPayloadUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType\RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader|null
     */
    private $rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader;
    /**
     * @param \PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType\RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader $rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader
     */
    public final function setRootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader($rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader) : void
    {
        $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader = $rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader;
    }
    protected final function getRootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader() : RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader */
        return $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader = $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader ?? $this->instanceManager->getInstance(RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'RootSetTagsOnPostMutationErrorPayloadUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting tags on a custom post', 'posttag-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getRootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader();
    }
}

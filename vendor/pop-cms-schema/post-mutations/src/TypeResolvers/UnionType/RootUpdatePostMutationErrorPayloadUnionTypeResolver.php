<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractRootUpdateCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\RelationalTypeDataLoaders\UnionType\RootUpdatePostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class RootUpdatePostMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateCustomPostMutationErrorPayloadUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\PostMutations\RelationalTypeDataLoaders\UnionType\RootUpdatePostMutationErrorPayloadUnionTypeDataLoader|null
     */
    private $rootUpdatePostMutationErrorPayloadUnionTypeDataLoader;
    /**
     * @param \PoPCMSSchema\PostMutations\RelationalTypeDataLoaders\UnionType\RootUpdatePostMutationErrorPayloadUnionTypeDataLoader $rootUpdatePostMutationErrorPayloadUnionTypeDataLoader
     */
    public final function setRootUpdatePostMutationErrorPayloadUnionTypeDataLoader($rootUpdatePostMutationErrorPayloadUnionTypeDataLoader) : void
    {
        $this->rootUpdatePostMutationErrorPayloadUnionTypeDataLoader = $rootUpdatePostMutationErrorPayloadUnionTypeDataLoader;
    }
    protected final function getRootUpdatePostMutationErrorPayloadUnionTypeDataLoader() : RootUpdatePostMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var RootUpdatePostMutationErrorPayloadUnionTypeDataLoader */
        return $this->rootUpdatePostMutationErrorPayloadUnionTypeDataLoader = $this->rootUpdatePostMutationErrorPayloadUnionTypeDataLoader ?? $this->instanceManager->getInstance(RootUpdatePostMutationErrorPayloadUnionTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'RootUpdatePostMutationErrorPayloadUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a post', 'post-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdatePostMutationErrorPayloadUnionTypeDataLoader();
    }
}

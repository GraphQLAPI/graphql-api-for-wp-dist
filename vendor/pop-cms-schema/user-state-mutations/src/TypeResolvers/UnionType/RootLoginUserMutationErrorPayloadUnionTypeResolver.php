<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\UnionType\RootLoginUserMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class RootLoginUserMutationErrorPayloadUnionTypeResolver extends \PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType\AbstractUserStateMutationErrorPayloadUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\UnionType\RootLoginUserMutationErrorPayloadUnionTypeDataLoader|null
     */
    private $rootLoginUserMutationErrorPayloadUnionTypeDataLoader;
    /**
     * @param \PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\UnionType\RootLoginUserMutationErrorPayloadUnionTypeDataLoader $rootLoginUserMutationErrorPayloadUnionTypeDataLoader
     */
    public final function setRootLoginUserMutationErrorPayloadUnionTypeDataLoader($rootLoginUserMutationErrorPayloadUnionTypeDataLoader) : void
    {
        $this->rootLoginUserMutationErrorPayloadUnionTypeDataLoader = $rootLoginUserMutationErrorPayloadUnionTypeDataLoader;
    }
    protected final function getRootLoginUserMutationErrorPayloadUnionTypeDataLoader() : RootLoginUserMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var RootLoginUserMutationErrorPayloadUnionTypeDataLoader */
        return $this->rootLoginUserMutationErrorPayloadUnionTypeDataLoader = $this->rootLoginUserMutationErrorPayloadUnionTypeDataLoader ?? $this->instanceManager->getInstance(RootLoginUserMutationErrorPayloadUnionTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'RootLoginUserMutationErrorPayloadUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'Error Payload\' types when logging a user in', 'user-state-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getRootLoginUserMutationErrorPayloadUnionTypeDataLoader();
    }
}

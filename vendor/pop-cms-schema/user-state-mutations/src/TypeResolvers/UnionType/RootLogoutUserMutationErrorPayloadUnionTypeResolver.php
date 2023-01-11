<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\UnionType\RootLogoutUserMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class RootLogoutUserMutationErrorPayloadUnionTypeResolver extends \PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType\AbstractUserStateMutationErrorPayloadUnionTypeResolver
{
    /**
     * @var \PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\UnionType\RootLogoutUserMutationErrorPayloadUnionTypeDataLoader|null
     */
    private $rootLogoutUserMutationErrorPayloadUnionTypeDataLoader;
    /**
     * @param \PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\UnionType\RootLogoutUserMutationErrorPayloadUnionTypeDataLoader $rootLogoutUserMutationErrorPayloadUnionTypeDataLoader
     */
    public final function setRootLogoutUserMutationErrorPayloadUnionTypeDataLoader($rootLogoutUserMutationErrorPayloadUnionTypeDataLoader) : void
    {
        $this->rootLogoutUserMutationErrorPayloadUnionTypeDataLoader = $rootLogoutUserMutationErrorPayloadUnionTypeDataLoader;
    }
    protected final function getRootLogoutUserMutationErrorPayloadUnionTypeDataLoader() : RootLogoutUserMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var RootLogoutUserMutationErrorPayloadUnionTypeDataLoader */
        return $this->rootLogoutUserMutationErrorPayloadUnionTypeDataLoader = $this->rootLogoutUserMutationErrorPayloadUnionTypeDataLoader ?? $this->instanceManager->getInstance(RootLogoutUserMutationErrorPayloadUnionTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'RootLogoutUserMutationErrorPayloadUnion';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Union of \'Error Payload\' types when logging a user out', 'user-state-mutations');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getRootLogoutUserMutationErrorPayloadUnionTypeDataLoader();
    }
}

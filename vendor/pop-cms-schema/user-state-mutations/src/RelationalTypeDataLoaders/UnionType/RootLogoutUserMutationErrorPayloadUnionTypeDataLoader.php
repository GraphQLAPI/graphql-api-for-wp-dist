<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType\RootLogoutUserMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class RootLogoutUserMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType\RootLogoutUserMutationErrorPayloadUnionTypeResolver|null
     */
    private $rootLogoutUserMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType\RootLogoutUserMutationErrorPayloadUnionTypeResolver $rootLogoutUserMutationErrorPayloadUnionTypeResolver
     */
    public final function setRootLogoutUserMutationErrorPayloadUnionTypeResolver($rootLogoutUserMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->rootLogoutUserMutationErrorPayloadUnionTypeResolver = $rootLogoutUserMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getRootLogoutUserMutationErrorPayloadUnionTypeResolver() : RootLogoutUserMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootLogoutUserMutationErrorPayloadUnionTypeResolver */
        return $this->rootLogoutUserMutationErrorPayloadUnionTypeResolver = $this->rootLogoutUserMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(RootLogoutUserMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getRootLogoutUserMutationErrorPayloadUnionTypeResolver();
    }
}

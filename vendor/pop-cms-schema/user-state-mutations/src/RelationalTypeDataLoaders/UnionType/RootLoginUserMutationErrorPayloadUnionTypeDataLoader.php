<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType\RootLoginUserMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class RootLoginUserMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType\RootLoginUserMutationErrorPayloadUnionTypeResolver|null
     */
    private $rootLoginUserMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType\RootLoginUserMutationErrorPayloadUnionTypeResolver $rootLoginUserMutationErrorPayloadUnionTypeResolver
     */
    public final function setRootLoginUserMutationErrorPayloadUnionTypeResolver($rootLoginUserMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->rootLoginUserMutationErrorPayloadUnionTypeResolver = $rootLoginUserMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getRootLoginUserMutationErrorPayloadUnionTypeResolver() : RootLoginUserMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootLoginUserMutationErrorPayloadUnionTypeResolver */
        return $this->rootLoginUserMutationErrorPayloadUnionTypeResolver = $this->rootLoginUserMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(RootLoginUserMutationErrorPayloadUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getRootLoginUserMutationErrorPayloadUnionTypeResolver();
    }
}

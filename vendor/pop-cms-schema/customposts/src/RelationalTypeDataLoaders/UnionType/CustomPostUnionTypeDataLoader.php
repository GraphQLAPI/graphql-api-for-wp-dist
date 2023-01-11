<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\UnionType;

use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
class CustomPostUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver|null
     */
    private $customPostUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver $customPostUnionTypeResolver
     */
    public final function setCustomPostUnionTypeResolver($customPostUnionTypeResolver) : void
    {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
    }
    protected final function getCustomPostUnionTypeResolver() : CustomPostUnionTypeResolver
    {
        /** @var CustomPostUnionTypeResolver */
        return $this->customPostUnionTypeResolver = $this->customPostUnionTypeResolver ?? $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
    }
    protected function getUnionTypeResolver() : UnionTypeResolverInterface
    {
        return $this->getCustomPostUnionTypeResolver();
    }
}

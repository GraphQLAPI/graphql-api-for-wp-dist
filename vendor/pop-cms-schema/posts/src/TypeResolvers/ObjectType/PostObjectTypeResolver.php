<?php

declare (strict_types=1);
namespace PoPCMSSchema\Posts\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\Posts\RelationalTypeDataLoaders\ObjectType\PostTypeDataLoader;
class PostObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\Posts\RelationalTypeDataLoaders\ObjectType\PostTypeDataLoader|null
     */
    private $postTypeDataLoader;
    /**
     * @param \PoPCMSSchema\Posts\RelationalTypeDataLoaders\ObjectType\PostTypeDataLoader $postTypeDataLoader
     */
    public final function setPostTypeDataLoader($postTypeDataLoader) : void
    {
        $this->postTypeDataLoader = $postTypeDataLoader;
    }
    protected final function getPostTypeDataLoader() : PostTypeDataLoader
    {
        /** @var PostTypeDataLoader */
        return $this->postTypeDataLoader = $this->postTypeDataLoader ?? $this->instanceManager->getInstance(PostTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'Post';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Representation of a post', 'posts');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getPostTypeDataLoader();
    }
}

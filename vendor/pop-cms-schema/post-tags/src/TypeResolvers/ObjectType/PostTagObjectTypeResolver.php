<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTags\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPCMSSchema\PostTags\RelationalTypeDataLoaders\ObjectType\PostTagTypeDataLoader;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\AbstractTagObjectTypeResolver;
class PostTagObjectTypeResolver extends AbstractTagObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\PostTags\RelationalTypeDataLoaders\ObjectType\PostTagTypeDataLoader|null
     */
    private $postTagTypeDataLoader;
    /**
     * @var \PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface|null
     */
    private $postTagTypeAPI;
    /**
     * @param \PoPCMSSchema\PostTags\RelationalTypeDataLoaders\ObjectType\PostTagTypeDataLoader $postTagTypeDataLoader
     */
    public final function setPostTagTypeDataLoader($postTagTypeDataLoader) : void
    {
        $this->postTagTypeDataLoader = $postTagTypeDataLoader;
    }
    protected final function getPostTagTypeDataLoader() : PostTagTypeDataLoader
    {
        /** @var PostTagTypeDataLoader */
        return $this->postTagTypeDataLoader = $this->postTagTypeDataLoader ?? $this->instanceManager->getInstance(PostTagTypeDataLoader::class);
    }
    /**
     * @param \PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface $postTagTypeAPI
     */
    public final function setPostTagTypeAPI($postTagTypeAPI) : void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    protected final function getPostTagTypeAPI() : PostTagTypeAPIInterface
    {
        /** @var PostTagTypeAPIInterface */
        return $this->postTagTypeAPI = $this->postTagTypeAPI ?? $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
    }
    public function getTagTypeAPI() : TagTypeAPIInterface
    {
        return $this->getPostTagTypeAPI();
    }
    public function getTypeName() : string
    {
        return 'PostTag';
    }
    public function getTypeDescription() : ?string
    {
        return \sprintf($this->__('Representation of a tag, added to a post (taxonomy: "%s")', 'post-tags'), $this->getPostTagTypeAPI()->getPostTagTaxonomyName());
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getPostTagTypeDataLoader();
    }
}

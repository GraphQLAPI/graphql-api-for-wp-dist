<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTags\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType\AbstractTagTypeDataLoader;
use PoPCMSSchema\Tags\TypeAPIs\TagListTypeAPIInterface;
class PostTagTypeDataLoader extends AbstractTagTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface|null
     */
    private $postTagTypeAPI;
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
    public function getTagListTypeAPI() : TagListTypeAPIInterface
    {
        return $this->getPostTagTypeAPI();
    }
}

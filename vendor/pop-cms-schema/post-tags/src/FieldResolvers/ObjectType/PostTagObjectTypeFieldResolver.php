<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTags\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\Tags\FieldResolvers\ObjectType\AbstractTagObjectTypeFieldResolver;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
class PostTagObjectTypeFieldResolver extends AbstractTagObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface|null
     */
    private $postTagTypeAPI;
    /**
     * @var \PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver|null
     */
    private $postTagObjectTypeResolver;
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
    /**
     * @param \PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver $postTagObjectTypeResolver
     */
    public final function setPostTagObjectTypeResolver($postTagObjectTypeResolver) : void
    {
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }
    protected final function getPostTagObjectTypeResolver() : PostTagObjectTypeResolver
    {
        /** @var PostTagObjectTypeResolver */
        return $this->postTagObjectTypeResolver = $this->postTagObjectTypeResolver ?? $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
    }
    public function getTagTypeAPI() : TagTypeAPIInterface
    {
        return $this->getPostTagTypeAPI();
    }
    public function getTagTypeResolver() : TagObjectTypeResolverInterface
    {
        return $this->getPostTagObjectTypeResolver();
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [PostTagObjectTypeResolver::class];
    }
}

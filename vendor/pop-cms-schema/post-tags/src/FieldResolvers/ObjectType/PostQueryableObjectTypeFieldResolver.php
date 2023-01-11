<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTags\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\Tags\FieldResolvers\ObjectType\AbstractCustomPostQueryableObjectTypeFieldResolver;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
class PostQueryableObjectTypeFieldResolver extends AbstractCustomPostQueryableObjectTypeFieldResolver
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
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [PostObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'tags':
                return $this->__('Tags added to this post', 'pop-post-tags');
            case 'tagCount':
                return $this->__('Number of tags added to this post', 'pop-post-tags');
            case 'tagNames':
                return $this->__('Names of the tags added to this post', 'pop-post-tags');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    public function getTagTypeAPI() : TagTypeAPIInterface
    {
        return $this->getPostTagTypeAPI();
    }
    public function getTagTypeResolver() : TagObjectTypeResolverInterface
    {
        return $this->getPostTagObjectTypeResolver();
    }
}

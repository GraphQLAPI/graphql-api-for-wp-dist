<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnCustomPostFilterInputObjectTypeResolver;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
abstract class AbstractSetTagsOnPostFilterInputObjectTypeResolver extends AbstractSetTagsOnCustomPostFilterInputObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver|null
     */
    private $postTagObjectTypeResolver;
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
    protected function getTagTypeResolver() : TagObjectTypeResolverInterface
    {
        return $this->getPostTagObjectTypeResolver();
    }
    protected function getEntityName() : string
    {
        return $this->__('post', 'postcategory-mutations');
    }
}

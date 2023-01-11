<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostCategories\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Categories\FieldResolvers\ObjectType\AbstractChildCategoryObjectTypeFieldResolver;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
class ChildPostCategoryObjectTypeFieldResolver extends AbstractChildCategoryObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface|null
     */
    private $postCategoryTypeAPI;
    /**
     * @var \PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver|null
     */
    private $postCategoryObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface $postCategoryTypeAPI
     */
    public final function setPostCategoryTypeAPI($postCategoryTypeAPI) : void
    {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
    }
    protected final function getPostCategoryTypeAPI() : PostCategoryTypeAPIInterface
    {
        /** @var PostCategoryTypeAPIInterface */
        return $this->postCategoryTypeAPI = $this->postCategoryTypeAPI ?? $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver
     */
    public final function setPostCategoryObjectTypeResolver($postCategoryObjectTypeResolver) : void
    {
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
    }
    protected final function getPostCategoryObjectTypeResolver() : PostCategoryObjectTypeResolver
    {
        /** @var PostCategoryObjectTypeResolver */
        return $this->postCategoryObjectTypeResolver = $this->postCategoryObjectTypeResolver ?? $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [PostCategoryObjectTypeResolver::class];
    }
    public function getCategoryTypeAPI() : CategoryTypeAPIInterface
    {
        return $this->getPostCategoryTypeAPI();
    }
    public function getCategoryTypeResolver() : CategoryObjectTypeResolverInterface
    {
        return $this->getPostCategoryObjectTypeResolver();
    }
}

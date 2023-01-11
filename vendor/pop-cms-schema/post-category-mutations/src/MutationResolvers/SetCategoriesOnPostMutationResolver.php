<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostCategoryMutations\MutationResolvers;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\AbstractSetCategoriesOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPCMSSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface;
class SetCategoriesOnPostMutationResolver extends AbstractSetCategoriesOnCustomPostMutationResolver
{
    /**
     * @var \PoPCMSSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface|null
     */
    private $postCategoryTypeMutationAPI;
    /**
     * @var \PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface|null
     */
    private $postCategoryTypeAPI;
    /**
     * @param \PoPCMSSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface $postCategoryTypeMutationAPI
     */
    public final function setPostCategoryTypeMutationAPI($postCategoryTypeMutationAPI) : void
    {
        $this->postCategoryTypeMutationAPI = $postCategoryTypeMutationAPI;
    }
    protected final function getPostCategoryTypeMutationAPI() : PostCategoryTypeMutationAPIInterface
    {
        /** @var PostCategoryTypeMutationAPIInterface */
        return $this->postCategoryTypeMutationAPI = $this->postCategoryTypeMutationAPI ?? $this->instanceManager->getInstance(PostCategoryTypeMutationAPIInterface::class);
    }
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
    protected function getCustomPostCategoryTypeMutationAPI() : CustomPostCategoryTypeMutationAPIInterface
    {
        return $this->getPostCategoryTypeMutationAPI();
    }
    protected function getCategoryTypeAPI() : CategoryTypeAPIInterface
    {
        return $this->getPostCategoryTypeAPI();
    }
    protected function getEntityName() : string
    {
        return $this->__('post', 'post-category-mutations');
    }
}

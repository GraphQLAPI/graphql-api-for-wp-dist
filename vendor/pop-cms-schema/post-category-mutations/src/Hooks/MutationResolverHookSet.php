<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostCategoryMutations\Hooks;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\CustomPostCategoryMutations\Hooks\AbstractMutationResolverHookSet;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPCMSSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
class MutationResolverHookSet extends AbstractMutationResolverHookSet
{
    /**
     * @var \PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface|null
     */
    private $postTypeAPI;
    /**
     * @var \PoPCMSSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface|null
     */
    private $postCategoryTypeMutationAPIInterface;
    /**
     * @var \PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface|null
     */
    private $postCategoryTypeAPI;
    /**
     * @param \PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface $postTypeAPI
     */
    public final function setPostTypeAPI($postTypeAPI) : void
    {
        $this->postTypeAPI = $postTypeAPI;
    }
    protected final function getPostTypeAPI() : PostTypeAPIInterface
    {
        /** @var PostTypeAPIInterface */
        return $this->postTypeAPI = $this->postTypeAPI ?? $this->instanceManager->getInstance(PostTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface $postCategoryTypeMutationAPIInterface
     */
    public final function setPostCategoryTypeMutationAPI($postCategoryTypeMutationAPIInterface) : void
    {
        $this->postCategoryTypeMutationAPIInterface = $postCategoryTypeMutationAPIInterface;
    }
    protected final function getPostCategoryTypeMutationAPI() : PostCategoryTypeMutationAPIInterface
    {
        /** @var PostCategoryTypeMutationAPIInterface */
        return $this->postCategoryTypeMutationAPIInterface = $this->postCategoryTypeMutationAPIInterface ?? $this->instanceManager->getInstance(PostCategoryTypeMutationAPIInterface::class);
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
    protected function getCustomPostType() : string
    {
        return $this->getPostTypeAPI()->getPostCustomPostType();
    }
    protected function getCustomPostCategoryTypeMutationAPI() : CustomPostCategoryTypeMutationAPIInterface
    {
        return $this->getPostCategoryTypeMutationAPI();
    }
    protected function getCategoryTypeAPI() : CategoryTypeAPIInterface
    {
        return $this->getPostCategoryTypeAPI();
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostCategories\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\AbstractCategoryObjectTypeResolver;
use PoPCMSSchema\PostCategories\RelationalTypeDataLoaders\ObjectType\PostCategoryTypeDataLoader;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
class PostCategoryObjectTypeResolver extends AbstractCategoryObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\PostCategories\RelationalTypeDataLoaders\ObjectType\PostCategoryTypeDataLoader|null
     */
    private $postCategoryTypeDataLoader;
    /**
     * @var \PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface|null
     */
    private $postCategoryTypeAPI;
    /**
     * @param \PoPCMSSchema\PostCategories\RelationalTypeDataLoaders\ObjectType\PostCategoryTypeDataLoader $postCategoryTypeDataLoader
     */
    public final function setPostCategoryTypeDataLoader($postCategoryTypeDataLoader) : void
    {
        $this->postCategoryTypeDataLoader = $postCategoryTypeDataLoader;
    }
    protected final function getPostCategoryTypeDataLoader() : PostCategoryTypeDataLoader
    {
        /** @var PostCategoryTypeDataLoader */
        return $this->postCategoryTypeDataLoader = $this->postCategoryTypeDataLoader ?? $this->instanceManager->getInstance(PostCategoryTypeDataLoader::class);
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
    public function getTypeName() : string
    {
        return 'PostCategory';
    }
    public function getTypeDescription() : ?string
    {
        return \sprintf($this->__('Representation of a category, added to a post (taxonomy: "%s")', 'post-categories'), $this->getPostCategoryTypeAPI()->getPostCategoryTaxonomyName());
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getPostCategoryTypeDataLoader();
    }
    public function getCategoryTypeAPI() : CategoryTypeAPIInterface
    {
        return $this->getPostCategoryTypeAPI();
    }
}

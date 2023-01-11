<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\InputObjectType\AbstractSetCategoriesOnCustomPostFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPCMSSchema\PostCategoryMutations\MutationResolvers\PayloadableSetCategoriesOnPostMutationResolver;
use PoPCMSSchema\PostCategoryMutations\MutationResolvers\SetCategoriesOnPostMutationResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType\PostSetCategoriesFilterInputObjectTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType\PostSetCategoriesMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
class PostObjectTypeFieldResolver extends AbstractCustomPostObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver|null
     */
    private $postObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\PostCategoryMutations\MutationResolvers\SetCategoriesOnPostMutationResolver|null
     */
    private $setCategoriesOnPostMutationResolver;
    /**
     * @var \PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver|null
     */
    private $postCategoryObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType\PostSetCategoriesFilterInputObjectTypeResolver|null
     */
    private $postSetCategoriesFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\PostCategoryMutations\MutationResolvers\PayloadableSetCategoriesOnPostMutationResolver|null
     */
    private $payloadableSetCategoriesOnPostMutationResolver;
    /**
     * @var \PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType\PostSetCategoriesMutationPayloadObjectTypeResolver|null
     */
    private $postSetCategoriesMutationPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver $postObjectTypeResolver
     */
    public final function setPostObjectTypeResolver($postObjectTypeResolver) : void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    protected final function getPostObjectTypeResolver() : PostObjectTypeResolver
    {
        /** @var PostObjectTypeResolver */
        return $this->postObjectTypeResolver = $this->postObjectTypeResolver ?? $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostCategoryMutations\MutationResolvers\SetCategoriesOnPostMutationResolver $setCategoriesOnPostMutationResolver
     */
    public final function setSetCategoriesOnPostMutationResolver($setCategoriesOnPostMutationResolver) : void
    {
        $this->setCategoriesOnPostMutationResolver = $setCategoriesOnPostMutationResolver;
    }
    protected final function getSetCategoriesOnPostMutationResolver() : SetCategoriesOnPostMutationResolver
    {
        /** @var SetCategoriesOnPostMutationResolver */
        return $this->setCategoriesOnPostMutationResolver = $this->setCategoriesOnPostMutationResolver ?? $this->instanceManager->getInstance(SetCategoriesOnPostMutationResolver::class);
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
     * @param \PoPCMSSchema\PostCategoryMutations\TypeResolvers\InputObjectType\PostSetCategoriesFilterInputObjectTypeResolver $postSetCategoriesFilterInputObjectTypeResolver
     */
    public final function setPostSetCategoriesFilterInputObjectTypeResolver($postSetCategoriesFilterInputObjectTypeResolver) : void
    {
        $this->postSetCategoriesFilterInputObjectTypeResolver = $postSetCategoriesFilterInputObjectTypeResolver;
    }
    protected final function getPostSetCategoriesFilterInputObjectTypeResolver() : AbstractSetCategoriesOnCustomPostFilterInputObjectTypeResolver
    {
        /** @var PostSetCategoriesFilterInputObjectTypeResolver */
        return $this->postSetCategoriesFilterInputObjectTypeResolver = $this->postSetCategoriesFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(PostSetCategoriesFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostCategoryMutations\MutationResolvers\PayloadableSetCategoriesOnPostMutationResolver $payloadableSetCategoriesOnPostMutationResolver
     */
    public final function setPayloadableSetCategoriesOnPostMutationResolver($payloadableSetCategoriesOnPostMutationResolver) : void
    {
        $this->payloadableSetCategoriesOnPostMutationResolver = $payloadableSetCategoriesOnPostMutationResolver;
    }
    protected final function getPayloadableSetCategoriesOnPostMutationResolver() : PayloadableSetCategoriesOnPostMutationResolver
    {
        /** @var PayloadableSetCategoriesOnPostMutationResolver */
        return $this->payloadableSetCategoriesOnPostMutationResolver = $this->payloadableSetCategoriesOnPostMutationResolver ?? $this->instanceManager->getInstance(PayloadableSetCategoriesOnPostMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType\PostSetCategoriesMutationPayloadObjectTypeResolver $postSetCategoriesMutationPayloadObjectTypeResolver
     */
    public final function setPostSetCategoriesMutationPayloadObjectTypeResolver($postSetCategoriesMutationPayloadObjectTypeResolver) : void
    {
        $this->postSetCategoriesMutationPayloadObjectTypeResolver = $postSetCategoriesMutationPayloadObjectTypeResolver;
    }
    protected final function getPostSetCategoriesMutationPayloadObjectTypeResolver() : PostSetCategoriesMutationPayloadObjectTypeResolver
    {
        /** @var PostSetCategoriesMutationPayloadObjectTypeResolver */
        return $this->postSetCategoriesMutationPayloadObjectTypeResolver = $this->postSetCategoriesMutationPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(PostSetCategoriesMutationPayloadObjectTypeResolver::class);
    }
    public function getCustomPostObjectTypeResolver() : CustomPostObjectTypeResolverInterface
    {
        return $this->getPostObjectTypeResolver();
    }
    public function getSetCategoriesMutationResolver() : MutationResolverInterface
    {
        return $this->getSetCategoriesOnPostMutationResolver();
    }
    public function getCategoryTypeResolver() : CategoryObjectTypeResolverInterface
    {
        return $this->getPostCategoryObjectTypeResolver();
    }
    public function getCustomPostSetCategoriesFilterInputObjectTypeResolver() : AbstractSetCategoriesOnCustomPostFilterInputObjectTypeResolver
    {
        return $this->getPostSetCategoriesFilterInputObjectTypeResolver();
    }
    protected function getCustomPostSetCategoriesMutationPayloadObjectTypeResolver() : ConcreteTypeResolverInterface
    {
        return $this->getPostSetCategoriesMutationPayloadObjectTypeResolver();
    }
    public function getPayloadableSetCategoriesMutationResolver() : MutationResolverInterface
    {
        return $this->getPayloadableSetCategoriesOnPostMutationResolver();
    }
    protected function getEntityName() : string
    {
        return $this->__('post', 'post-category-mutations');
    }
}

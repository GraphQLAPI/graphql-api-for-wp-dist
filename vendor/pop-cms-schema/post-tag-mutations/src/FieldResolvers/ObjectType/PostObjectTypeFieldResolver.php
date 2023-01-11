<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType\AbstractCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnCustomPostFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPCMSSchema\PostTagMutations\MutationResolvers\PayloadableSetTagsOnPostMutationResolver;
use PoPCMSSchema\PostTagMutations\MutationResolvers\SetTagsOnPostMutationResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType\PostSetTagsFilterInputObjectTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\PostSetTagsMutationPayloadObjectTypeResolver;
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
     * @var \PoPCMSSchema\PostTagMutations\MutationResolvers\SetTagsOnPostMutationResolver|null
     */
    private $setTagsOnPostMutationResolver;
    /**
     * @var \PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType\PostSetTagsFilterInputObjectTypeResolver|null
     */
    private $postSetTagsFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\PostTagMutations\MutationResolvers\PayloadableSetTagsOnPostMutationResolver|null
     */
    private $payloadableSetTagsOnPostMutationResolver;
    /**
     * @var \PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\PostSetTagsMutationPayloadObjectTypeResolver|null
     */
    private $postSetTagsMutationPayloadObjectTypeResolver;
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
     * @param \PoPCMSSchema\PostTagMutations\MutationResolvers\SetTagsOnPostMutationResolver $setTagsOnPostMutationResolver
     */
    public final function setSetTagsOnPostMutationResolver($setTagsOnPostMutationResolver) : void
    {
        $this->setTagsOnPostMutationResolver = $setTagsOnPostMutationResolver;
    }
    protected final function getSetTagsOnPostMutationResolver() : SetTagsOnPostMutationResolver
    {
        /** @var SetTagsOnPostMutationResolver */
        return $this->setTagsOnPostMutationResolver = $this->setTagsOnPostMutationResolver ?? $this->instanceManager->getInstance(SetTagsOnPostMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType\PostSetTagsFilterInputObjectTypeResolver $postSetTagsFilterInputObjectTypeResolver
     */
    public final function setPostSetTagsFilterInputObjectTypeResolver($postSetTagsFilterInputObjectTypeResolver) : void
    {
        $this->postSetTagsFilterInputObjectTypeResolver = $postSetTagsFilterInputObjectTypeResolver;
    }
    protected final function getPostSetTagsFilterInputObjectTypeResolver() : AbstractSetTagsOnCustomPostFilterInputObjectTypeResolver
    {
        /** @var PostSetTagsFilterInputObjectTypeResolver */
        return $this->postSetTagsFilterInputObjectTypeResolver = $this->postSetTagsFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(PostSetTagsFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostTagMutations\MutationResolvers\PayloadableSetTagsOnPostMutationResolver $payloadableSetTagsOnPostMutationResolver
     */
    public final function setPayloadableSetTagsOnPostMutationResolver($payloadableSetTagsOnPostMutationResolver) : void
    {
        $this->payloadableSetTagsOnPostMutationResolver = $payloadableSetTagsOnPostMutationResolver;
    }
    protected final function getPayloadableSetTagsOnPostMutationResolver() : PayloadableSetTagsOnPostMutationResolver
    {
        /** @var PayloadableSetTagsOnPostMutationResolver */
        return $this->payloadableSetTagsOnPostMutationResolver = $this->payloadableSetTagsOnPostMutationResolver ?? $this->instanceManager->getInstance(PayloadableSetTagsOnPostMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\PostSetTagsMutationPayloadObjectTypeResolver $postSetTagsMutationPayloadObjectTypeResolver
     */
    public final function setPostSetTagsMutationPayloadObjectTypeResolver($postSetTagsMutationPayloadObjectTypeResolver) : void
    {
        $this->postSetTagsMutationPayloadObjectTypeResolver = $postSetTagsMutationPayloadObjectTypeResolver;
    }
    protected final function getPostSetTagsMutationPayloadObjectTypeResolver() : PostSetTagsMutationPayloadObjectTypeResolver
    {
        /** @var PostSetTagsMutationPayloadObjectTypeResolver */
        return $this->postSetTagsMutationPayloadObjectTypeResolver = $this->postSetTagsMutationPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(PostSetTagsMutationPayloadObjectTypeResolver::class);
    }
    public function getCustomPostObjectTypeResolver() : CustomPostObjectTypeResolverInterface
    {
        return $this->getPostObjectTypeResolver();
    }
    public function getSetTagsMutationResolver() : MutationResolverInterface
    {
        return $this->getSetTagsOnPostMutationResolver();
    }
    public function getCustomPostSetTagsFilterInputObjectTypeResolver() : AbstractSetTagsOnCustomPostFilterInputObjectTypeResolver
    {
        return $this->getPostSetTagsFilterInputObjectTypeResolver();
    }
    protected function getCustomPostSetTagsMutationPayloadObjectTypeResolver() : ConcreteTypeResolverInterface
    {
        return $this->getPostSetTagsMutationPayloadObjectTypeResolver();
    }
    public function getPayloadableSetTagsMutationResolver() : MutationResolverInterface
    {
        return $this->getPayloadableSetTagsOnPostMutationResolver();
    }
    protected function getEntityName() : string
    {
        return $this->__('post', 'post-tag-mutations');
    }
}

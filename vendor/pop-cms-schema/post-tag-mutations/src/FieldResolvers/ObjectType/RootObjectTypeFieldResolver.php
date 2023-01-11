<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType\AbstractRootObjectTypeFieldResolver;
use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnCustomPostFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPCMSSchema\PostTagMutations\MutationResolvers\PayloadableSetTagsOnPostMutationResolver;
use PoPCMSSchema\PostTagMutations\MutationResolvers\SetTagsOnPostMutationResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType\RootSetTagsOnCustomPostFilterInputObjectTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\RootSetTagsOnPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
class RootObjectTypeFieldResolver extends AbstractRootObjectTypeFieldResolver
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
     * @var \PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType\RootSetTagsOnCustomPostFilterInputObjectTypeResolver|null
     */
    private $rootSetTagsOnCustomPostFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\PostTagMutations\MutationResolvers\PayloadableSetTagsOnPostMutationResolver|null
     */
    private $payloadableSetTagsOnPostMutationResolver;
    /**
     * @var \PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\RootSetTagsOnPostMutationPayloadObjectTypeResolver|null
     */
    private $rootSetTagsOnPostMutationPayloadObjectTypeResolver;
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
     * @param \PoPCMSSchema\PostTagMutations\TypeResolvers\InputObjectType\RootSetTagsOnCustomPostFilterInputObjectTypeResolver $rootSetTagsOnCustomPostFilterInputObjectTypeResolver
     */
    public final function setRootSetTagsOnCustomPostFilterInputObjectTypeResolver($rootSetTagsOnCustomPostFilterInputObjectTypeResolver) : void
    {
        $this->rootSetTagsOnCustomPostFilterInputObjectTypeResolver = $rootSetTagsOnCustomPostFilterInputObjectTypeResolver;
    }
    protected final function getRootSetTagsOnCustomPostFilterInputObjectTypeResolver() : AbstractSetTagsOnCustomPostFilterInputObjectTypeResolver
    {
        /** @var RootSetTagsOnCustomPostFilterInputObjectTypeResolver */
        return $this->rootSetTagsOnCustomPostFilterInputObjectTypeResolver = $this->rootSetTagsOnCustomPostFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootSetTagsOnCustomPostFilterInputObjectTypeResolver::class);
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
     * @param \PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\RootSetTagsOnPostMutationPayloadObjectTypeResolver $rootSetTagsOnPostMutationPayloadObjectTypeResolver
     */
    public final function setRootSetTagsOnPostMutationPayloadObjectTypeResolver($rootSetTagsOnPostMutationPayloadObjectTypeResolver) : void
    {
        $this->rootSetTagsOnPostMutationPayloadObjectTypeResolver = $rootSetTagsOnPostMutationPayloadObjectTypeResolver;
    }
    protected final function getRootSetTagsOnPostMutationPayloadObjectTypeResolver() : RootSetTagsOnPostMutationPayloadObjectTypeResolver
    {
        /** @var RootSetTagsOnPostMutationPayloadObjectTypeResolver */
        return $this->rootSetTagsOnPostMutationPayloadObjectTypeResolver = $this->rootSetTagsOnPostMutationPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(RootSetTagsOnPostMutationPayloadObjectTypeResolver::class);
    }
    public function getCustomPostObjectTypeResolver() : CustomPostObjectTypeResolverInterface
    {
        return $this->getPostObjectTypeResolver();
    }
    public function getSetTagsMutationResolver() : MutationResolverInterface
    {
        return $this->getSetTagsOnPostMutationResolver();
    }
    public function getPayloadableSetTagsMutationResolver() : MutationResolverInterface
    {
        return $this->getPayloadableSetTagsOnPostMutationResolver();
    }
    protected function getRootSetTagsMutationPayloadObjectTypeResolver() : ConcreteTypeResolverInterface
    {
        return $this->getRootSetTagsOnPostMutationPayloadObjectTypeResolver();
    }
    protected function getEntityName() : string
    {
        return $this->__('post', 'post-tag-mutations');
    }
    public function getCustomPostSetTagsFilterInputObjectTypeResolver() : AbstractSetTagsOnCustomPostFilterInputObjectTypeResolver
    {
        return $this->getRootSetTagsOnCustomPostFilterInputObjectTypeResolver();
    }
    protected function getSetTagsFieldName() : string
    {
        return 'setTagsOnPost';
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTags\ObjectTypeResolverPickers;

use PoPCMSSchema\Tags\ObjectTypeResolverPickers\TagObjectTypeResolverPickerInterface;
use PoPCMSSchema\Tags\ObjectTypeResolverPickers\TagObjectTypeResolverPickerTrait;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractPostTagObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker implements TagObjectTypeResolverPickerInterface
{
    use TagObjectTypeResolverPickerTrait;
    /**
     * @var \PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver|null
     */
    private $postTagObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface|null
     */
    private $postTagTypeAPI;
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
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getPostTagObjectTypeResolver();
    }
    /**
     * @param object $object
     */
    public function isInstanceOfType($object) : bool
    {
        return $this->getPostTagTypeAPI()->isInstanceOfTagType($object);
    }
    /**
     * @param string|int $objectID
     */
    public function isIDOfType($objectID) : bool
    {
        return $this->getPostTagTypeAPI()->tagExists($objectID);
    }
    public function getTagTaxonomy() : string
    {
        return $this->getPostTagTypeAPI()->getPostTagTaxonomyName();
    }
}

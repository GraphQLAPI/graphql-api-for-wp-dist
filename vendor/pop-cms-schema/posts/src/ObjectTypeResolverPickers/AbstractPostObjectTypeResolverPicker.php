<?php

declare (strict_types=1);
namespace PoPCMSSchema\Posts\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
abstract class AbstractPostObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    /**
     * @var \PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver|null
     */
    private $postObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface|null
     */
    private $postTypeAPI;
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
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getPostObjectTypeResolver();
    }
    /**
     * @param object $object
     */
    public function isInstanceOfType($object) : bool
    {
        return $this->getPostTypeAPI()->isInstanceOfPostType($object);
    }
    /**
     * @param string|int $objectID
     */
    public function isIDOfType($objectID) : bool
    {
        return $this->getPostTypeAPI()->postExists($objectID);
    }
}

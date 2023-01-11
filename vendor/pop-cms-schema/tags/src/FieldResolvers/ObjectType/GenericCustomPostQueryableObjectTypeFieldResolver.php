<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPCMSSchema\Tags\ComponentProcessors\TagFilterInputContainerComponentProcessor;
use PoPCMSSchema\Tags\FieldResolvers\ObjectType\AbstractCustomPostQueryableObjectTypeFieldResolver;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class GenericCustomPostQueryableObjectTypeFieldResolver extends AbstractCustomPostQueryableObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface|null
     */
    private $queryableTagTypeAPI;
    /**
     * @var \PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver|null
     */
    private $genericTagObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface $queryableTagTypeAPI
     */
    public final function setQueryableTagTypeAPI($queryableTagTypeAPI) : void
    {
        $this->queryableTagTypeAPI = $queryableTagTypeAPI;
    }
    protected final function getQueryableTagTypeAPI() : QueryableTagTypeAPIInterface
    {
        /** @var QueryableTagTypeAPIInterface */
        return $this->queryableTagTypeAPI = $this->queryableTagTypeAPI ?? $this->instanceManager->getInstance(QueryableTagTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver $genericTagObjectTypeResolver
     */
    public final function setGenericTagObjectTypeResolver($genericTagObjectTypeResolver) : void
    {
        $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
    }
    protected final function getGenericTagObjectTypeResolver() : GenericTagObjectTypeResolver
    {
        /** @var GenericTagObjectTypeResolver */
        return $this->genericTagObjectTypeResolver = $this->genericTagObjectTypeResolver ?? $this->instanceManager->getInstance(GenericTagObjectTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [GenericCustomPostObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName) : ?Component
    {
        switch ($fieldName) {
            case 'tags':
            case 'tagCount':
            case 'tagNames':
                return new Component(TagFilterInputContainerComponentProcessor::class, TagFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GENERICTAGS);
            default:
                return parent::getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'tags':
                return $this->__('Tags added to this custom post', 'pop-post-tags');
            case 'tagCount':
                return $this->__('Number of tags added to this custom post', 'pop-post-tags');
            case 'tagNames':
                return $this->__('Names of the tags added to this custom post', 'pop-post-tags');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    public function getTagTypeAPI() : TagTypeAPIInterface
    {
        return $this->getQueryableTagTypeAPI();
    }
    public function getTagTypeResolver() : TagObjectTypeResolverInterface
    {
        return $this->getGenericTagObjectTypeResolver();
    }
}

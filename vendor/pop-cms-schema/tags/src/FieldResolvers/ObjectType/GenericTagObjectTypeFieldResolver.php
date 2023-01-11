<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\FieldResolvers\ObjectType;

use PoPCMSSchema\Tags\FieldResolvers\ObjectType\AbstractTagObjectTypeFieldResolver;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\TagObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class GenericTagObjectTypeFieldResolver extends AbstractTagObjectTypeFieldResolver
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
    public function getTagTypeAPI() : TagTypeAPIInterface
    {
        return $this->getQueryableTagTypeAPI();
    }
    public function getTagTypeResolver() : TagObjectTypeResolverInterface
    {
        return $this->getGenericTagObjectTypeResolver();
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [GenericTagObjectTypeResolver::class];
    }
}

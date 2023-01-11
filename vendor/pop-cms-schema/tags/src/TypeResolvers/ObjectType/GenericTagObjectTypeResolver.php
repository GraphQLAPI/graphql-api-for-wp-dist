<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\TypeResolvers\ObjectType;

use PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType\QueryableTagListTypeDataLoader;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
/**
 * Class to be used only when a Generic Tag Type is good enough.
 * Otherwise, a specific type for the entity should be employed.
 */
class GenericTagObjectTypeResolver extends \PoPCMSSchema\Tags\TypeResolvers\ObjectType\AbstractTagObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType\QueryableTagListTypeDataLoader|null
     */
    private $queryableTagListTypeDataLoader;
    /**
     * @var \PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface|null
     */
    private $queryableTagListTypeAPI;
    /**
     * @param \PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType\QueryableTagListTypeDataLoader $queryableTagListTypeDataLoader
     */
    public final function setQueryableTagListTypeDataLoader($queryableTagListTypeDataLoader) : void
    {
        $this->queryableTagListTypeDataLoader = $queryableTagListTypeDataLoader;
    }
    protected final function getQueryableTagListTypeDataLoader() : QueryableTagListTypeDataLoader
    {
        /** @var QueryableTagListTypeDataLoader */
        return $this->queryableTagListTypeDataLoader = $this->queryableTagListTypeDataLoader ?? $this->instanceManager->getInstance(QueryableTagListTypeDataLoader::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface $queryableTagListTypeAPI
     */
    public final function setQueryableTagTypeAPI($queryableTagListTypeAPI) : void
    {
        $this->queryableTagListTypeAPI = $queryableTagListTypeAPI;
    }
    protected final function getQueryableTagTypeAPI() : QueryableTagTypeAPIInterface
    {
        /** @var QueryableTagTypeAPIInterface */
        return $this->queryableTagListTypeAPI = $this->queryableTagListTypeAPI ?? $this->instanceManager->getInstance(QueryableTagTypeAPIInterface::class);
    }
    public function getTypeName() : string
    {
        return 'GenericTag';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('A tag that does not have its own type in the schema', 'customposts');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getQueryableTagListTypeDataLoader();
    }
    public function getTagTypeAPI() : TagTypeAPIInterface
    {
        return $this->getQueryableTagTypeAPI();
    }
}

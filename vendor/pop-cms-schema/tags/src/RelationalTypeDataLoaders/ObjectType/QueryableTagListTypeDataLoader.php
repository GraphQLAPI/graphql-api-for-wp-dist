<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType\AbstractTagTypeDataLoader;
use PoPCMSSchema\Tags\TypeAPIs\TagListTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
class QueryableTagListTypeDataLoader extends AbstractTagTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface|null
     */
    private $queryableTagListTypeAPI;
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
    public function getTagListTypeAPI() : TagListTypeAPIInterface
    {
        return $this->getQueryableTagTypeAPI();
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\Categories\RelationalTypeDataLoaders\ObjectType\AbstractCategoryTypeDataLoader;
use PoPCMSSchema\Categories\TypeAPIs\CategoryListTypeAPIInterface;
use PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface;
class QueryableCategoryListTypeDataLoader extends AbstractCategoryTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface|null
     */
    private $queryableCategoryListTypeAPI;
    /**
     * @param \PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface $queryableCategoryListTypeAPI
     */
    public final function setQueryableCategoryTypeAPI($queryableCategoryListTypeAPI) : void
    {
        $this->queryableCategoryListTypeAPI = $queryableCategoryListTypeAPI;
    }
    protected final function getQueryableCategoryTypeAPI() : QueryableCategoryTypeAPIInterface
    {
        /** @var QueryableCategoryTypeAPIInterface */
        return $this->queryableCategoryListTypeAPI = $this->queryableCategoryListTypeAPI ?? $this->instanceManager->getInstance(QueryableCategoryTypeAPIInterface::class);
    }
    public function getCategoryListTypeAPI() : CategoryListTypeAPIInterface
    {
        return $this->getQueryableCategoryTypeAPI();
    }
}

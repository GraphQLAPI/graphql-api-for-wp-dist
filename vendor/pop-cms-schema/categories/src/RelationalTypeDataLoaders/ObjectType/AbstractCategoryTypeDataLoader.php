<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;
use PoPCMSSchema\Categories\TypeAPIs\CategoryListTypeAPIInterface;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
abstract class AbstractCategoryTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    public abstract function getCategoryListTypeAPI() : CategoryListTypeAPIInterface;
    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs($ids) : array
    {
        return ['include' => $ids];
    }
    protected function getOrderbyDefault() : string
    {
        return $this->getNameResolver()->getName('popcms:dbcolumn:orderby:categories:count');
    }
    protected function getOrderDefault() : string
    {
        return 'DESC';
    }
    /**
     * @return mixed[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function executeQuery($query, $options = []) : array
    {
        return $this->getCategoryListTypeAPI()->getCategories($query, $options);
    }
    /**
     * @param array<string,mixed> $query
     * @return array<string|int>
     */
    public function executeQueryIDs($query) : array
    {
        $options = [QueryOptions::RETURN_TYPE => ReturnTypes::IDS];
        return $this->executeQuery($query, $options);
    }
}

<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType;

use PoP\Root\App;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
abstract class AbstractCustomPostTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface|null
     */
    private $customPostTypeAPI;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver|null
     */
    private $filterCustomPostStatusEnumTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface $customPostTypeAPI
     */
    public final function setCustomPostTypeAPI($customPostTypeAPI) : void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    protected final function getCustomPostTypeAPI() : CustomPostTypeAPIInterface
    {
        /** @var CustomPostTypeAPIInterface */
        return $this->customPostTypeAPI = $this->customPostTypeAPI ?? $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver $filterCustomPostStatusEnumTypeResolver
     */
    public final function setFilterCustomPostStatusEnumTypeResolver($filterCustomPostStatusEnumTypeResolver) : void
    {
        $this->filterCustomPostStatusEnumTypeResolver = $filterCustomPostStatusEnumTypeResolver;
    }
    protected final function getFilterCustomPostStatusEnumTypeResolver() : FilterCustomPostStatusEnumTypeResolver
    {
        /** @var FilterCustomPostStatusEnumTypeResolver */
        return $this->filterCustomPostStatusEnumTypeResolver = $this->filterCustomPostStatusEnumTypeResolver ?? $this->instanceManager->getInstance(FilterCustomPostStatusEnumTypeResolver::class);
    }
    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs($ids) : array
    {
        return ['include' => $ids, 'status' => $this->getFilterCustomPostStatusEnumTypeResolver()->getConsolidatedEnumValues()];
    }
    /**
     * @return mixed[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function executeQuery($query, $options = []) : array
    {
        return $this->getCustomPostTypeAPI()->getCustomPosts($query, $options);
    }
    protected function getOrderbyDefault() : string
    {
        return $this->getNameResolver()->getName('popcms:dbcolumn:orderby:customposts:date');
    }
    protected function getOrderDefault() : string
    {
        return 'DESC';
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
    /**
     * @param array<string,mixed> $query_args
     */
    protected function getLimitParam($query_args) : int
    {
        return App::applyFilters('CustomPostTypeDataLoader:query:limit', parent::getLimitParam($query_args));
    }
    protected function getQueryHookName() : string
    {
        // Allow to add the timestamp for loadingLatest
        return 'CustomPostTypeDataLoader:query';
    }
}

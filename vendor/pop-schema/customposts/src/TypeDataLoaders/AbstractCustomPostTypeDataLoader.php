<?php

declare (strict_types=1);
namespace PoPSchema\CustomPosts\TypeDataLoaders;

use PoPSchema\CustomPosts\Types\Status;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostRelationalFieldDataloadModuleProcessor;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
abstract class AbstractCustomPostTypeDataLoader extends \PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader
{
    public function getFilterDataloadingModule() : ?array
    {
        return [\PoPSchema\CustomPosts\ModuleProcessors\CustomPostRelationalFieldDataloadModuleProcessor::class, \PoPSchema\CustomPosts\ModuleProcessors\CustomPostRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CUSTOMPOSTLIST];
    }
    public function getObjectQuery(array $ids) : array
    {
        $customPostTypeAPI = \PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade::getInstance();
        return array(
            'include' => $ids,
            // If not adding the post types, WordPress only uses "post", so querying by CPT would fail loading data
            // This should be considered for the CMS-agnostic case if it makes sense
            'custompost-types' => $customPostTypeAPI->getCustomPostTypes(['publicly-queryable' => \true]),
        );
    }
    public function getObjects(array $ids) : array
    {
        $customPostTypeAPI = \PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade::getInstance();
        $query = $this->getObjectQuery($ids);
        return $customPostTypeAPI->getCustomPosts($query);
    }
    public function getDataFromIdsQuery(array $ids) : array
    {
        $query = array();
        $query['include'] = $ids;
        $query['status'] = [\PoPSchema\CustomPosts\Types\Status::PUBLISHED, \PoPSchema\CustomPosts\Types\Status::DRAFT, \PoPSchema\CustomPosts\Types\Status::PENDING];
        // Status can also be 'pending', so don't limit it here, just select by ID
        return $query;
    }
    public function executeQuery($query, array $options = [])
    {
        $customPostTypeAPI = \PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI->getCustomPosts($query, $options);
    }
    protected function getOrderbyDefault()
    {
        return \PoP\LooseContracts\Facades\NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:customposts:date');
    }
    protected function getOrderDefault()
    {
        return 'DESC';
    }
    public function executeQueryIds($query) : array
    {
        $options = ['return-type' => \PoPSchema\SchemaCommons\DataLoading\ReturnTypes::IDS];
        return (array) $this->executeQuery($query, $options);
    }
    protected function getLimitParam($query_args)
    {
        return \PoP\Hooks\Facades\HooksAPIFacade::getInstance()->applyFilters('CustomPostTypeDataLoader:query:limit', parent::getLimitParam($query_args));
    }
    protected function getQueryHookName()
    {
        // Allow to add the timestamp for loadingLatest
        return 'CustomPostTypeDataLoader:query';
    }
}

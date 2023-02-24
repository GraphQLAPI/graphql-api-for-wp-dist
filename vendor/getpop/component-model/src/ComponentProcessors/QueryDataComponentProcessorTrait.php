<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Constants\DataSources;
use PoP\ComponentModel\Constants\HookNames;
use PoP\ComponentModel\Constants\PaginationParams;
use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\ObjectTypeQueryableDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
trait QueryDataComponentProcessorTrait
{
    use \PoP\ComponentModel\ComponentProcessors\FilterDataComponentProcessorTrait;
    protected abstract function getActionExecutionQueryInputOutputHandler() : ActionExecutionQueryInputOutputHandler;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    protected function getImmutableDataloadQueryArgs($component, &$props) : array
    {
        return array();
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    protected function getMutableonrequestDataloadQueryArgs($component, &$props) : array
    {
        return array();
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getQueryInputOutputHandler($component) : ?QueryInputOutputHandlerInterface
    {
        return $this->getActionExecutionQueryInputOutputHandler();
    }
    // public function getFilter(\PoP\ComponentModel\Component\Component $component)
    // {
    //     return null;
    // }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getImmutableHeaddatasetcomponentDataProperties($component, &$props) : array
    {
        $ret = parent::getImmutableHeaddatasetcomponentDataProperties($component, $props);
        // Attributes to pass to the query
        $ret[\PoP\ComponentModel\ComponentProcessors\DataloadingConstants::QUERYARGS] = $this->getImmutableDataloadQueryArgs($component, $props);
        return $ret;
    }
    /**
     * @return Component[]
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getQueryArgsFilteringComponents($component, &$props) : array
    {
        // Attributes overriding the query args, taken from the request
        return [$component];
    }
    /**
     * @return mixed[]
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonmodelHeaddatasetcomponentDataProperties($component, &$props) : array
    {
        $ret = parent::getMutableonmodelHeaddatasetcomponentDataProperties($component, $props);
        // Attributes overriding the query args, taken from the request
        if (!isset($ret[\PoP\ComponentModel\ComponentProcessors\DataloadingConstants::IGNOREREQUESTPARAMS]) || !$ret[\PoP\ComponentModel\ComponentProcessors\DataloadingConstants::IGNOREREQUESTPARAMS]) {
            $ret[\PoP\ComponentModel\ComponentProcessors\DataloadingConstants::QUERYARGSFILTERINGCOMPONENTS] = $this->getQueryArgsFilteringComponents($component, $props);
        }
        // // Set the filter if it has one
        // if ($filter = $this->getFilter($component)) {
        //     $ret[GD_DATALOAD_FILTER] = $filter;
        // }
        return $ret;
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonrequestHeaddatasetcomponentDataProperties($component, &$props) : array
    {
        $ret = parent::getMutableonrequestHeaddatasetcomponentDataProperties($component, $props);
        $ret[\PoP\ComponentModel\ComponentProcessors\DataloadingConstants::QUERYARGS] = $this->getMutableonrequestDataloadQueryArgs($component, $props);
        return $ret;
    }
    /**
     * @return string|int|array<string|int>|null
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getObjectIDOrIDs($component, &$props, &$data_properties)
    {
        // Prepare the Query to get data from the DB
        $datasource = $data_properties[\PoP\ComponentModel\ComponentProcessors\DataloadingConstants::DATASOURCE] ?? null;
        if ($datasource === DataSources::MUTABLEONREQUEST && !($data_properties[\PoP\ComponentModel\ComponentProcessors\DataloadingConstants::IGNOREREQUESTPARAMS] ?? null)) {
            // Merge with $_POST/$_GET, so that params passed through the URL can be used for the query (eg: ?limit=5)
            // But whitelist the params that can be taken, to avoid hackers peering inside the system and getting custom data (eg: params "include", "post-status" => "draft", etc)
            $whitelisted_params = (array) App::applyFilters(HookNames::QUERYDATA_WHITELISTEDPARAMS, [PaginationParams::PAGE_NUMBER, PaginationParams::LIMIT]);
            $params_from_request = \array_filter(\array_merge(App::getRequest()->query->all(), App::getRequest()->request->all()), function (string $param) use($whitelisted_params) {
                return \in_array($param, $whitelisted_params);
            }, \ARRAY_FILTER_USE_KEY);
            // Finally merge it into the data properties
            $data_properties[\PoP\ComponentModel\ComponentProcessors\DataloadingConstants::QUERYARGS] = \array_merge($data_properties[\PoP\ComponentModel\ComponentProcessors\DataloadingConstants::QUERYARGS], $params_from_request);
        }
        if ($queryHandler = $this->getQueryInputOutputHandler($component)) {
            // Allow the queryHandler to override/normalize the query args
            $queryHandler->prepareQueryArgs($data_properties[\PoP\ComponentModel\ComponentProcessors\DataloadingConstants::QUERYARGS]);
        }
        $relationalTypeResolver = $this->getRelationalTypeResolver($component);
        if ($relationalTypeResolver === null) {
            return null;
        }
        /** @var ObjectTypeQueryableDataLoaderInterface */
        $typeDataLoader = $relationalTypeResolver->getRelationalTypeDataLoader();
        return $typeDataLoader->findIDs($data_properties);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public abstract function getRelationalTypeResolver($component) : ?RelationalTypeResolverInterface;
    /**
     * @param array<string,mixed> $ret
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param string|int|array<string|int> $objectIDOrIDs
     * @param array<string,mixed>|null $executed
     * @return mixed[]
     * @param \PoP\ComponentModel\Component\Component $component
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $dataaccess_checkpoint_validation
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $actionexecution_checkpoint_validation
     */
    public function addQueryHandlerDatasetmeta($ret, $component, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs) : array
    {
        $queryHandler = $this->getQueryInputOutputHandler($component);
        if ($queryHandler === null) {
            return $ret;
        }
        if ($query_state = $queryHandler->getQueryState($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs)) {
            $ret['querystate'] = $query_state;
        }
        if ($query_params = $queryHandler->getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs)) {
            $ret['queryparams'] = $query_params;
        }
        if ($query_result = $queryHandler->getQueryResult($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs)) {
            $ret['queryresult'] = $query_result;
        }
        return $ret;
    }
}

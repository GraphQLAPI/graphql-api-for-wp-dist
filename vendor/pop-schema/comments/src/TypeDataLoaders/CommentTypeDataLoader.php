<?php

declare (strict_types=1);
namespace PoPSchema\Comments\TypeDataLoaders;

use PoPSchema\Comments\Constants\Status;
use PoPSchema\Comments\Constants\Params;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoPSchema\Comments\ModuleProcessors\CommentRelationalFieldDataloadModuleProcessor;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
class CommentTypeDataLoader extends \PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader
{
    public function getFilterDataloadingModule() : ?array
    {
        return [\PoPSchema\Comments\ModuleProcessors\CommentRelationalFieldDataloadModuleProcessor::class, \PoPSchema\Comments\ModuleProcessors\CommentRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_COMMENTS];
    }
    public function getObjects(array $ids) : array
    {
        $cmscommentsapi = \PoPSchema\Comments\FunctionAPIFactory::getInstance();
        $query = ['include' => $ids];
        return $cmscommentsapi->getComments($query);
    }
    public function getQuery($query_args) : array
    {
        $query = parent::getQuery($query_args);
        $query['status'] = \PoPSchema\Comments\Constants\Status::APPROVED;
        // $query['type'] = 'comment'; // Only comments, no trackbacks or pingbacks
        $query['customPostID'] = $query_args[\PoPSchema\Comments\Constants\Params::COMMENT_POST_ID];
        return $query;
    }
    public function getDataFromIdsQuery(array $ids) : array
    {
        $query = array();
        $query['include'] = $ids;
        return $query;
    }
    public function executeQuery($query, array $options = [])
    {
        $cmscommentsapi = \PoPSchema\Comments\FunctionAPIFactory::getInstance();
        return $cmscommentsapi->getComments($query, $options);
    }
    public function executeQueryIds($query) : array
    {
        $options = ['return-type' => \PoPSchema\SchemaCommons\DataLoading\ReturnTypes::IDS];
        return (array) $this->executeQuery($query, $options);
    }
}

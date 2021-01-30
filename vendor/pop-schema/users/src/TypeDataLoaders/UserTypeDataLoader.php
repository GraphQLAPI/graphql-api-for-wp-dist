<?php

declare (strict_types=1);
namespace PoPSchema\Users\TypeDataLoaders;

use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
class UserTypeDataLoader extends \PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader
{
    public function getFilterDataloadingModule() : ?array
    {
        return [\PrefixedByPoP\PoP_Users_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_Users_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST];
    }
    public function getObjects(array $ids) : array
    {
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $ret = array();
        foreach ($ids as $user_id) {
            $ret[] = $cmsusersapi->getUserById($user_id);
        }
        return $ret;
    }
    public function getDataFromIdsQuery(array $ids) : array
    {
        $query = array('include' => $ids);
        return $query;
    }
    protected function getOrderbyDefault()
    {
        return \PoP\LooseContracts\Facades\NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:users:name');
    }
    protected function getOrderDefault()
    {
        return 'ASC';
    }
    protected function getQueryHookName()
    {
        // Get the role either from a provided attr, and allow PoP User Platform to set the default role
        return 'UserTypeDataLoader:query';
    }
    public function executeQuery($query, array $options = [])
    {
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        return $cmsusersapi->getUsers($query, $options);
    }
    public function executeQueryIds($query) : array
    {
        // $query['fields'] = 'ID';
        $options = ['return-type' => \PoPSchema\SchemaCommons\DataLoading\ReturnTypes::IDS];
        return (array) $this->executeQuery($query, $options);
    }
}

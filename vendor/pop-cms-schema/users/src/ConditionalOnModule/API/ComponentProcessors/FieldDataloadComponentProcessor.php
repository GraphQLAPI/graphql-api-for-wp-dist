<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\ConditionalOnModule\API\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;
use PoPCMSSchema\Users\ComponentProcessors\UserFilterInputContainerComponentProcessor;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
class FieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEUSER = 'dataload-relationalfields-singleuser';
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST = 'dataload-relationalfields-userlist';
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_USERCOUNT = 'dataload-relationalfields-usercount';
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST = 'dataload-relationalfields-adminuserlist';
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERCOUNT = 'dataload-relationalfields-adminusercount';
    /**
     * @var \PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver|null
     */
    private $userObjectTypeResolver;
    /**
     * @var \PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler|null
     */
    private $listQueryInputOutputHandler;
    /**
     * @param \PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver $userObjectTypeResolver
     */
    public final function setUserObjectTypeResolver($userObjectTypeResolver) : void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    protected final function getUserObjectTypeResolver() : UserObjectTypeResolver
    {
        /** @var UserObjectTypeResolver */
        return $this->userObjectTypeResolver = $this->userObjectTypeResolver ?? $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler $listQueryInputOutputHandler
     */
    public final function setListQueryInputOutputHandler($listQueryInputOutputHandler) : void
    {
        $this->listQueryInputOutputHandler = $listQueryInputOutputHandler;
    }
    protected final function getListQueryInputOutputHandler() : ListQueryInputOutputHandler
    {
        /** @var ListQueryInputOutputHandler */
        return $this->listQueryInputOutputHandler = $this->listQueryInputOutputHandler ?? $this->instanceManager->getInstance(ListQueryInputOutputHandler::class);
    }
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEUSER, self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST, self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERCOUNT, self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST, self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERCOUNT);
    }
    /**
     * @return string|int|array<string|int>|null
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getObjectIDOrIDs($component, &$props, &$data_properties)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEUSER:
                return $this->getQueriedDBObjectID();
        }
        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getRelationalTypeResolver($component) : ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEUSER:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST:
                return $this->getUserObjectTypeResolver();
        }
        return parent::getRelationalTypeResolver($component);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getQueryInputOutputHandler($component) : ?QueryInputOutputHandlerInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST:
                return $this->getListQueryInputOutputHandler();
        }
        return parent::getQueryInputOutputHandler($component);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterSubcomponent($component) : ?Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERLIST:
                return new Component(UserFilterInputContainerComponentProcessor::class, UserFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_USERS);
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_USERCOUNT:
                return new Component(UserFilterInputContainerComponentProcessor::class, UserFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_USERCOUNT);
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERLIST:
                return new Component(UserFilterInputContainerComponentProcessor::class, UserFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERS);
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINUSERCOUNT:
                return new Component(UserFilterInputContainerComponentProcessor::class, UserFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINUSERCOUNT);
        }
        return parent::getFilterSubcomponent($component);
    }
}

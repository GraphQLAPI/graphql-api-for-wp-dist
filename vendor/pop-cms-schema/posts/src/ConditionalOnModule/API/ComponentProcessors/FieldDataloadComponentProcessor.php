<?php

declare (strict_types=1);
namespace PoPCMSSchema\Posts\ConditionalOnModule\API\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Posts\ComponentProcessors\PostFilterInputContainerComponentProcessor;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;
class FieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEPOST = 'dataload-relationalfields-singlepost';
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST = 'dataload-relationalfields-postlist';
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_POSTCOUNT = 'dataload-relationalfields-postcount';
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST = 'dataload-relationalfields-adminpostlist';
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTCOUNT = 'dataload-relationalfields-adminpostcount';
    /**
     * @var \PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver|null
     */
    private $postObjectTypeResolver;
    /**
     * @var \PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler|null
     */
    private $listQueryInputOutputHandler;
    /**
     * @param \PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver $postObjectTypeResolver
     */
    public final function setPostObjectTypeResolver($postObjectTypeResolver) : void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    protected final function getPostObjectTypeResolver() : PostObjectTypeResolver
    {
        /** @var PostObjectTypeResolver */
        return $this->postObjectTypeResolver = $this->postObjectTypeResolver ?? $this->instanceManager->getInstance(PostObjectTypeResolver::class);
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
        return array(self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEPOST, self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST, self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTCOUNT, self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST, self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTCOUNT);
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
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEPOST:
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
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_SINGLEPOST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTCOUNT:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTCOUNT:
                return $this->getPostObjectTypeResolver();
        }
        return parent::getRelationalTypeResolver($component);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getQueryInputOutputHandler($component) : ?QueryInputOutputHandlerInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST:
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST:
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
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTLIST:
                return new Component(PostFilterInputContainerComponentProcessor::class, PostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_POSTS);
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_POSTCOUNT:
                return new Component(PostFilterInputContainerComponentProcessor::class, PostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_POSTCOUNT);
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTLIST:
                return new Component(PostFilterInputContainerComponentProcessor::class, PostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTS);
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ADMINPOSTCOUNT:
                return new Component(PostFilterInputContainerComponentProcessor::class, PostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_ADMINPOSTCOUNT);
        }
        return parent::getFilterSubcomponent($component);
    }
}

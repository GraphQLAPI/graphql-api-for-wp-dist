<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostCategories\ConditionalOnModule\API\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Posts\ComponentProcessors\PostFilterInputContainerComponentProcessor;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;
class CategoryPostFieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST = 'dataload-relationalfields-categorypostlist';
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
        return array(self::COMPONENT_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getRelationalTypeResolver($component) : ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST:
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
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST:
                return $this->getListQueryInputOutputHandler();
        }
        return parent::getQueryInputOutputHandler($component);
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    protected function getMutableonrequestDataloadQueryArgs($component, &$props) : array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST:
                $ret['category-ids'] = [App::getState(['routing', 'queried-object-id'])];
                break;
        }
        return $ret;
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterSubcomponent($component) : ?Component
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_CATEGORYPOSTLIST:
                return new Component(PostFilterInputContainerComponentProcessor::class, PostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_POSTS);
        }
        return parent::getFilterSubcomponent($component);
    }
}

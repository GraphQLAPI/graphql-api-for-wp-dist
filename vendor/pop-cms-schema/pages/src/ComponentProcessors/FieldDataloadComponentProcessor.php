<?php

declare (strict_types=1);
namespace PoPCMSSchema\Pages\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;
class FieldDataloadComponentProcessor extends AbstractRelationalFieldDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_PAGE = 'dataload-relationalfields-page';
    /**
     * @var \PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver|null
     */
    private $pageObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver $pageObjectTypeResolver
     */
    public final function setPageObjectTypeResolver($pageObjectTypeResolver) : void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }
    protected final function getPageObjectTypeResolver() : PageObjectTypeResolver
    {
        /** @var PageObjectTypeResolver */
        return $this->pageObjectTypeResolver = $this->pageObjectTypeResolver ?? $this->instanceManager->getInstance(PageObjectTypeResolver::class);
    }
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_DATALOAD_RELATIONALFIELDS_PAGE);
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
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_PAGE:
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
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_PAGE:
                return $this->getPageObjectTypeResolver();
        }
        return parent::getRelationalTypeResolver($component);
    }
}

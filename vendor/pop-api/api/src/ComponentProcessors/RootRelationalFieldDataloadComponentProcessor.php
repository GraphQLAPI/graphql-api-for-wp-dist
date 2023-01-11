<?php

declare (strict_types=1);
namespace PoPAPI\API\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\ObjectModels\Root;
use PoP\Engine\Schema\SchemaDefinitionServiceInterface;
class RootRelationalFieldDataloadComponentProcessor extends \PoPAPI\API\ComponentProcessors\AbstractRelationalFieldDataloadComponentProcessor
{
    public const COMPONENT_DATALOAD_RELATIONALFIELDS_ROOT = 'dataload-relationalfields-root';
    /**
     * @var \PoP\Engine\Schema\SchemaDefinitionServiceInterface|null
     */
    private $schemaDefinitionService;
    /**
     * @param \PoP\Engine\Schema\SchemaDefinitionServiceInterface $schemaDefinitionService
     */
    public final function setSchemaDefinitionService($schemaDefinitionService) : void
    {
        $this->schemaDefinitionService = $schemaDefinitionService;
    }
    protected final function getSchemaDefinitionService() : SchemaDefinitionServiceInterface
    {
        /** @var SchemaDefinitionServiceInterface */
        return $this->schemaDefinitionService = $this->schemaDefinitionService ?? $this->instanceManager->getInstance(SchemaDefinitionServiceInterface::class);
    }
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_DATALOAD_RELATIONALFIELDS_ROOT);
    }
    /**
     * @return string|int|array<string|int>|null
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getObjectIDOrIDs($component, &$props, &$data_properties)
    {
        if (App::getState('does-api-query-have-errors')) {
            return null;
        }
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ROOT:
                return Root::ID;
        }
        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getRelationalTypeResolver($component) : ?RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_RELATIONALFIELDS_ROOT:
                return $this->getSchemaDefinitionService()->getSchemaRootObjectTypeResolver();
        }
        return parent::getRelationalTypeResolver($component);
    }
}

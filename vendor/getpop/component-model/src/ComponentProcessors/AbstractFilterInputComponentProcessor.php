<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
abstract class AbstractFilterInputComponentProcessor extends \PoP\ComponentModel\ComponentProcessors\AbstractFormInputComponentProcessor implements \PoP\ComponentModel\ComponentProcessors\FilterInputComponentProcessorInterface
{
    /**
     * @var \PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface|null
     */
    private $schemaDefinitionService;
    /**
     * @param \PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface $schemaDefinitionService
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
     * @param \PoP\ComponentModel\Component\Component $component
     */
    protected function getFilterInputSchemaDefinitionResolver($component) : \PoP\ComponentModel\ComponentProcessors\FilterInputComponentProcessorInterface
    {
        return $this;
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputTypeResolver($component) : InputTypeResolverInterface
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($component);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputTypeResolver($component);
        }
        return $this->getDefaultSchemaFilterInputTypeResolver();
    }
    protected function getDefaultSchemaFilterInputTypeResolver() : InputTypeResolverInterface
    {
        return $this->getSchemaDefinitionService()->getDefaultInputTypeResolver();
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputDescription($component) : ?string
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($component);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputDescription($component);
        }
        return null;
    }
    /**
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputDefaultValue($component)
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($component);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputDefaultValue($component);
        }
        return null;
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputTypeModifiers($component) : int
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($component);
        if ($filterSchemaDefinitionResolver !== $this) {
            return $filterSchemaDefinitionResolver->getFilterInputTypeModifiers($component);
        }
        return SchemaTypeModifiers::NONE;
    }
}

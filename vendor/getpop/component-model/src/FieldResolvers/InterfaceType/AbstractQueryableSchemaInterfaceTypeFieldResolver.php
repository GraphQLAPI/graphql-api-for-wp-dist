<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;
abstract class AbstractQueryableSchemaInterfaceTypeFieldResolver extends \PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver implements \PoP\ComponentModel\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldSchemaDefinitionResolverInterface
{
    use QueryableFieldResolverTrait;
    /**
     * @var \PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface|null
     */
    private $componentProcessorManager;
    /**
     * @param \PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface $componentProcessorManager
     */
    public final function setComponentProcessorManager($componentProcessorManager) : void
    {
        $this->componentProcessorManager = $componentProcessorManager;
    }
    protected final function getComponentProcessorManager() : ComponentProcessorManagerInterface
    {
        /** @var ComponentProcessorManagerInterface */
        return $this->componentProcessorManager = $this->componentProcessorManager ?? $this->instanceManager->getInstance(ComponentProcessorManagerInterface::class);
    }
    /**
     * @param string $fieldName
     */
    public function getFieldFilterInputContainerComponent($fieldName) : ?Component
    {
        /**
         * An interface may implement another interface which is not Queryable
         */
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if (!$schemaDefinitionResolver instanceof \PoP\ComponentModel\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldSchemaDefinitionResolverInterface) {
            return null;
        }
        /** @var QueryableInterfaceTypeFieldSchemaDefinitionResolverInterface $schemaDefinitionResolver */
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldFilterInputContainerComponent($fieldName);
        }
        return null;
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($fieldName) : array
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($fieldName)) {
            return $this->getFilterFieldArgNameTypeResolvers($filterDataloadingComponent);
        }
        return parent::getFieldArgNameTypeResolvers($fieldName);
    }
    /**
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($fieldName, $fieldArgName) : ?string
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($fieldName)) {
            return $this->getFilterFieldArgDescription($filterDataloadingComponent, $fieldArgName);
        }
        return parent::getFieldArgDescription($fieldName, $fieldArgName);
    }
    /**
     * @return mixed
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDefaultValue($fieldName, $fieldArgName)
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($fieldName)) {
            return $this->getFilterFieldArgDefaultValue($filterDataloadingComponent, $fieldArgName);
        }
        return parent::getFieldArgDefaultValue($fieldName, $fieldArgName);
    }
    /**
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($fieldName, $fieldArgName) : int
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($fieldName)) {
            return $this->getFilterFieldArgTypeModifiers($filterDataloadingComponent, $fieldArgName);
        }
        return parent::getFieldArgTypeModifiers($fieldName, $fieldArgName);
    }
}

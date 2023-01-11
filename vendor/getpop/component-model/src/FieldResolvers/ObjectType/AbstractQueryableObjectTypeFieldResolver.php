<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\ComponentProcessors\FilterDataComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\FilterInputContainerComponentProcessorInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Resolvers\InterfaceSchemaDefinitionResolverAdapter;
use PoP\ComponentModel\Resolvers\QueryableFieldResolverTrait;
use PoP\ComponentModel\Resolvers\QueryableInterfaceSchemaDefinitionResolverAdapter;
use PoP\ComponentModel\TypeResolvers\InputObjectType\QueryableInputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;
abstract class AbstractQueryableObjectTypeFieldResolver extends \PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver implements \PoP\ComponentModel\FieldResolvers\ObjectType\QueryableObjectTypeFieldSchemaDefinitionResolverInterface
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
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName) : ?Component
    {
        /** @var QueryableObjectTypeFieldSchemaDefinitionResolverInterface */
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName);
        }
        return null;
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName)) {
            return $this->getFilterFieldArgNameTypeResolvers($filterDataloadingComponent);
        }
        return parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName)) {
            return $this->getFilterFieldArgDescription($filterDataloadingComponent, $fieldArgName);
        }
        return parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName);
    }
    /**
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName)
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName)) {
            return $this->getFilterFieldArgDefaultValue($filterDataloadingComponent, $fieldArgName);
        }
        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName)) {
            return $this->getFilterFieldArgTypeModifiers($filterDataloadingComponent, $fieldArgName);
        }
        return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
    }
    /**
     * @return class-string<InterfaceSchemaDefinitionResolverAdapter>
     */
    protected function getInterfaceSchemaDefinitionResolverAdapterClass() : string
    {
        return QueryableInterfaceSchemaDefinitionResolverAdapter::class;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function enableOrderedSchemaFieldArgs($objectTypeResolver, $fieldName) : bool
    {
        // If there is a filter, and it has many filterInputs, then by default we'd rather not enable ordering
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName)) {
            /** @var FilterInputContainerComponentProcessorInterface */
            $filterDataComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($filterDataloadingComponent);
            if (\count($filterDataComponentProcessor->getFilterInputComponents($filterDataloadingComponent)) > 1) {
                return \false;
            }
        }
        return parent::enableOrderedSchemaFieldArgs($objectTypeResolver, $fieldName);
    }
    /**
     * The names of the inputs supplied in the fieldArgs are not necessarily the same
     * input names expected by the function to retrieve entities in the Type API.
     *
     * For instance, input with name "searchfor" is translated as query arg "search"
     * when executing `PostTypeAPI->getPosts($query)`.
     *
     * This function transforms between the 2 states:
     *
     * - For each FilterInput defined via `getFieldFilterInputContainerComponent`:
     *   - Check if the entry with that name exists in fieldArgs, and if so:
     *     - Execute `filterDataloadQueryArgs` on the FilterInput to place the value
     *       under the expected input name
     *
     * @return array<string,mixed>
     * @throws AbstractValueResolutionPromiseException
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor) : array
    {
        $filteringQueryArgs = [];
        $fieldName = $fieldDataAccessor->getFieldName();
        $fieldArgs = $fieldDataAccessor->getFieldArgs();
        if ($filterDataloadingComponent = $this->getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName)) {
            /** @var FilterDataComponentProcessorInterface */
            $filterDataComponentProcessor = $this->getComponentProcessorManager()->getComponentProcessor($filterDataloadingComponent);
            $filterDataComponentProcessor->filterHeadcomponentDataloadQueryArgs($filterDataloadingComponent, $filteringQueryArgs, $fieldArgs);
        }
        // InputObjects can also provide filtering query values
        $consolidatedFieldArgNameTypeResolvers = $this->getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        foreach ($fieldArgs as $argName => $argValue) {
            $fieldArgTypeResolver = $consolidatedFieldArgNameTypeResolvers[$argName];
            if (!$fieldArgTypeResolver instanceof QueryableInputObjectTypeResolverInterface) {
                continue;
            }
            $fieldArgTypeResolver->integrateInputValueToFilteringQueryArgs($filteringQueryArgs, $argValue);
        }
        return $filteringQueryArgs;
    }
}

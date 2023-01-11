<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\AbstractFieldResolver;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\Resolvers\CheckDangerouslyNonSpecificScalarTypeFieldOrFieldDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrFieldDirectiveResolverTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;
abstract class AbstractInterfaceTypeFieldResolver extends AbstractFieldResolver implements \PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface
{
    use AttachableExtensionTrait;
    use WithVersionConstraintFieldOrFieldDirectiveResolverTrait;
    use FieldOrDirectiveSchemaDefinitionResolverTrait;
    use CheckDangerouslyNonSpecificScalarTypeFieldOrFieldDirectiveResolverTrait;
    /** @var array<string,array<string,mixed>> */
    protected $schemaDefinitionForFieldCache = [];
    /** @var array<string,string|null> */
    protected $consolidatedFieldDescriptionCache = [];
    /** @var array<string,array<string,mixed>> */
    protected $consolidatedFieldExtensionsCache = [];
    /** @var array<string,string|null> */
    protected $consolidatedFieldDeprecationMessageCache = [];
    /** @var array<string,array<string,InputTypeResolverInterface>> */
    protected $consolidatedFieldArgNameTypeResolversCache = [];
    /** @var array<string,string[]> */
    protected $consolidatedSensitiveFieldArgNamesCache = [];
    /** @var array<string,string|null> */
    protected $consolidatedFieldArgDescriptionCache = [];
    /** @var array<string,string|null> */
    protected $consolidatedFieldArgDeprecationMessageCache = [];
    /** @var array<string,mixed> */
    protected $consolidatedFieldArgDefaultValueCache = [];
    /** @var array<string,int> */
    protected $consolidatedFieldArgTypeModifiersCache = [];
    /** @var array<string,array<string,mixed>> */
    protected $consolidatedFieldArgExtensionsCache = [];
    /** @var array<string,array<string,mixed>> */
    protected $schemaFieldArgsCache = [];
    /**
     * @var InterfaceTypeResolverInterface[]|null
     */
    protected $partiallyImplementedInterfaceTypeResolvers;
    /**
     * @var \PoP\LooseContracts\NameResolverInterface|null
     */
    private $nameResolver;
    /**
     * @var \PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface|null
     */
    private $schemaNamespacingService;
    /**
     * @var \PoP\ComponentModel\Registries\TypeRegistryInterface|null
     */
    private $typeRegistry;
    /**
     * @var \PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface|null
     */
    private $schemaDefinitionService;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver|null
     */
    private $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface|null
     */
    private $attachableExtensionManager;
    /**
     * @param \PoP\LooseContracts\NameResolverInterface $nameResolver
     */
    public final function setNameResolver($nameResolver) : void
    {
        $this->nameResolver = $nameResolver;
    }
    protected final function getNameResolver() : NameResolverInterface
    {
        /** @var NameResolverInterface */
        return $this->nameResolver = $this->nameResolver ?? $this->instanceManager->getInstance(NameResolverInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface $schemaNamespacingService
     */
    public final function setSchemaNamespacingService($schemaNamespacingService) : void
    {
        $this->schemaNamespacingService = $schemaNamespacingService;
    }
    protected final function getSchemaNamespacingService() : SchemaNamespacingServiceInterface
    {
        /** @var SchemaNamespacingServiceInterface */
        return $this->schemaNamespacingService = $this->schemaNamespacingService ?? $this->instanceManager->getInstance(SchemaNamespacingServiceInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\Registries\TypeRegistryInterface $typeRegistry
     */
    public final function setTypeRegistry($typeRegistry) : void
    {
        $this->typeRegistry = $typeRegistry;
    }
    protected final function getTypeRegistry() : TypeRegistryInterface
    {
        /** @var TypeRegistryInterface */
        return $this->typeRegistry = $this->typeRegistry ?? $this->instanceManager->getInstance(TypeRegistryInterface::class);
    }
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
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver
     */
    public final function setDangerouslyNonSpecificScalarTypeScalarTypeResolver($dangerouslyNonSpecificScalarTypeScalarTypeResolver) : void
    {
        $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    }
    protected final function getDangerouslyNonSpecificScalarTypeScalarTypeResolver() : DangerouslyNonSpecificScalarTypeScalarTypeResolver
    {
        /** @var DangerouslyNonSpecificScalarTypeScalarTypeResolver */
        return $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver ?? $this->instanceManager->getInstance(DangerouslyNonSpecificScalarTypeScalarTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface $attachableExtensionManager
     */
    public final function setAttachableExtensionManager($attachableExtensionManager) : void
    {
        $this->attachableExtensionManager = $attachableExtensionManager;
    }
    protected final function getAttachableExtensionManager() : AttachableExtensionManagerInterface
    {
        /** @var AttachableExtensionManagerInterface */
        return $this->attachableExtensionManager = $this->attachableExtensionManager ?? $this->instanceManager->getInstance(AttachableExtensionManagerInterface::class);
    }
    /**
     * The InterfaceTypes the InterfaceTypeFieldResolver adds fields to
     *
     * @return string[]
     */
    public final function getClassesToAttachTo() : array
    {
        return $this->getInterfaceTypeResolverClassesToAttachTo();
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return $this->getFieldNamesToImplement();
    }
    /**
     * The interfaces the fieldResolver implements
     *
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers() : array
    {
        return [];
    }
    /**
     * Each InterfaceTypeFieldResolver provides a list of fieldNames to the Interface.
     * The Interface may also accept other fieldNames from other InterfaceTypeFieldResolvers.
     * That's why this function is "partially" implemented: the Interface
     * may be completely implemented or not.
     *
     * @return InterfaceTypeResolverInterface[]
     */
    public final function getPartiallyImplementedInterfaceTypeResolvers() : array
    {
        if ($this->partiallyImplementedInterfaceTypeResolvers === null) {
            // Search all the InterfaceTypeResolvers who either are, or inherit from,
            // any class from getInterfaceTypeResolverClassesToAttachTo
            $this->partiallyImplementedInterfaceTypeResolvers = [];
            $interfaceTypeResolverClassesToAttachTo = $this->getInterfaceTypeResolverClassesToAttachTo();
            $interfaceTypeResolvers = $this->getTypeRegistry()->getInterfaceTypeResolvers();
            foreach ($interfaceTypeResolvers as $interfaceTypeResolver) {
                $interfaceTypeResolverClass = \get_class($interfaceTypeResolver);
                foreach ($interfaceTypeResolverClassesToAttachTo as $interfaceTypeResolverClassToAttachTo) {
                    $interfaceTypeResolverClassParents = \class_parents($interfaceTypeResolverClass);
                    if ($interfaceTypeResolverClass === $interfaceTypeResolverClassToAttachTo || $interfaceTypeResolverClassParents !== \false && \in_array($interfaceTypeResolverClassToAttachTo, $interfaceTypeResolverClassParents)) {
                        $this->partiallyImplementedInterfaceTypeResolvers[] = $interfaceTypeResolver;
                        break;
                    }
                }
            }
        }
        return $this->partiallyImplementedInterfaceTypeResolvers;
    }
    /**
     * By default, the resolver is this same object, unless function
     * `getInterfaceTypeFieldSchemaDefinitionResolver` is
     * implemented
     * @param string $fieldName
     */
    protected function getSchemaDefinitionResolver($fieldName) : \PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldSchemaDefinitionResolverInterface
    {
        if ($interfaceTypeFieldSchemaDefinitionResolver = $this->getInterfaceTypeFieldSchemaDefinitionResolver($fieldName)) {
            return $interfaceTypeFieldSchemaDefinitionResolver;
        }
        return $this;
    }
    /**
     * Retrieve the InterfaceTypeFieldSchemaDefinitionResolverInterface
     * By default, if the InterfaceTypeFieldResolver implements an interface,
     * it is used as SchemaDefinitionResolver for the matching fields
     * @param string $fieldName
     */
    protected function getInterfaceTypeFieldSchemaDefinitionResolver($fieldName) : ?\PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldSchemaDefinitionResolverInterface
    {
        foreach ($this->getImplementedInterfaceTypeFieldResolvers() as $implementedInterfaceTypeFieldResolver) {
            if (!\in_array($fieldName, $implementedInterfaceTypeFieldResolver->getFieldNamesToImplement())) {
                continue;
            }
            /** @var InterfaceTypeFieldSchemaDefinitionResolverInterface */
            return $implementedInterfaceTypeFieldResolver;
        }
        return null;
    }
    /**
     * @param string $fieldName
     */
    public function getFieldTypeResolver($fieldName) : ConcreteTypeResolverInterface
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldTypeResolver($fieldName);
        }
        return $this->getSchemaDefinitionService()->getDefaultConcreteTypeResolver();
    }
    /**
     * @param string $fieldName
     */
    public function getFieldDescription($fieldName) : ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldDescription($fieldName);
        }
        return null;
    }
    /**
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($fieldName) : int
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldTypeModifiers($fieldName);
        }
        return SchemaTypeModifiers::NONE;
    }
    /**
     * @param string $fieldName
     */
    public function getFieldDeprecationMessage($fieldName) : ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldDeprecationMessage($fieldName);
        }
        return null;
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($fieldName) : array
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgNameTypeResolvers($fieldName);
        }
        return [];
    }
    /**
     * @return string[]
     * @param string $fieldName
     */
    public function getSensitiveFieldArgNames($fieldName) : array
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getSensitiveFieldArgNames($fieldName);
        }
        return [];
    }
    /**
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($fieldName, $fieldArgName) : ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgDescription($fieldName, $fieldArgName);
        }
        return null;
    }
    /**
     * @return mixed
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDefaultValue($fieldName, $fieldArgName)
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgDefaultValue($fieldName, $fieldArgName);
        }
        return null;
    }
    /**
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($fieldName, $fieldArgName) : int
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgTypeModifiers($fieldName, $fieldArgName);
        }
        return SchemaTypeModifiers::NONE;
    }
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface|string $fieldOrFieldName
     */
    public function isFieldGlobal($fieldOrFieldName) : bool
    {
        if ($fieldOrFieldName instanceof FieldInterface) {
            $field = $fieldOrFieldName;
            $fieldName = $field->getName();
        } else {
            $fieldName = $fieldOrFieldName;
        }
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->isFieldGlobal($fieldOrFieldName);
        }
        return \false;
    }
    /**
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface|string $fieldOrFieldName
     */
    public function isFieldAMutation($fieldOrFieldName) : bool
    {
        if ($fieldOrFieldName instanceof FieldInterface) {
            $field = $fieldOrFieldName;
            $fieldName = $field->getName();
        } else {
            $fieldName = $fieldOrFieldName;
        }
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->isFieldAMutation($fieldOrFieldName);
        }
        return \false;
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @param string $fieldName
     */
    public final function getConsolidatedFieldArgNameTypeResolvers($fieldName) : array
    {
        // Cache the result
        $cacheKey = $fieldName;
        if (\array_key_exists($cacheKey, $this->consolidatedFieldArgNameTypeResolversCache)) {
            return $this->consolidatedFieldArgNameTypeResolversCache[$cacheKey];
        }
        /**
         * Allow to override/extend the inputs (eg: module "Post Categories" can add
         * input "categories" to field "Root.createPost")
         */
        $consolidatedFieldArgNameTypeResolvers = App::applyFilters(\PoP\ComponentModel\FieldResolvers\InterfaceType\HookNames::INTERFACE_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS, $this->getFieldArgNameTypeResolvers($fieldName), $this, $fieldName);
        // Exclude the sensitive field args, if "Admin" Schema is not enabled
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->exposeSensitiveDataInSchema()) {
            $sensitiveFieldArgNames = $this->getConsolidatedSensitiveFieldArgNames($fieldName);
            $consolidatedFieldArgNameTypeResolvers = \array_filter($consolidatedFieldArgNameTypeResolvers, function (string $fieldArgName) use($sensitiveFieldArgNames) {
                return !\in_array($fieldArgName, $sensitiveFieldArgNames);
            }, \ARRAY_FILTER_USE_KEY);
        }
        $this->consolidatedFieldArgNameTypeResolversCache[$cacheKey] = $consolidatedFieldArgNameTypeResolvers;
        return $this->consolidatedFieldArgNameTypeResolversCache[$cacheKey];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @param string $fieldName
     */
    public final function getConsolidatedSensitiveFieldArgNames($fieldName) : array
    {
        // Cache the result
        $cacheKey = $fieldName;
        if (\array_key_exists($cacheKey, $this->consolidatedSensitiveFieldArgNamesCache)) {
            return $this->consolidatedSensitiveFieldArgNamesCache[$cacheKey];
        }
        $this->consolidatedSensitiveFieldArgNamesCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\InterfaceType\HookNames::INTERFACE_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS, $this->getSensitiveFieldArgNames($fieldName), $this, $fieldName);
        return $this->consolidatedSensitiveFieldArgNamesCache[$cacheKey];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public final function getConsolidatedFieldArgDescription($fieldName, $fieldArgName) : ?string
    {
        // Cache the result
        $cacheKey = $fieldName . '(' . $fieldArgName . ':)';
        if (\array_key_exists($cacheKey, $this->consolidatedFieldArgDescriptionCache)) {
            return $this->consolidatedFieldArgDescriptionCache[$cacheKey];
        }
        $this->consolidatedFieldArgDescriptionCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\InterfaceType\HookNames::INTERFACE_TYPE_FIELD_ARG_DESCRIPTION, $this->getFieldArgDescription($fieldName, $fieldArgName), $this, $fieldName, $fieldArgName);
        return $this->consolidatedFieldArgDescriptionCache[$cacheKey];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @return mixed
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public final function getConsolidatedFieldArgDefaultValue($fieldName, $fieldArgName)
    {
        // Cache the result
        $cacheKey = $fieldName . '(' . $fieldArgName . ':)';
        if (\array_key_exists($cacheKey, $this->consolidatedFieldArgDefaultValueCache)) {
            return $this->consolidatedFieldArgDefaultValueCache[$cacheKey];
        }
        $this->consolidatedFieldArgDefaultValueCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\InterfaceType\HookNames::INTERFACE_TYPE_FIELD_ARG_DEFAULT_VALUE, $this->getFieldArgDefaultValue($fieldName, $fieldArgName), $this, $fieldName, $fieldArgName);
        return $this->consolidatedFieldArgDefaultValueCache[$cacheKey];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public final function getConsolidatedFieldArgTypeModifiers($fieldName, $fieldArgName) : int
    {
        // Cache the result
        $cacheKey = $fieldName . '(' . $fieldArgName . ':)';
        if (\array_key_exists($cacheKey, $this->consolidatedFieldArgTypeModifiersCache)) {
            return $this->consolidatedFieldArgTypeModifiersCache[$cacheKey];
        }
        $this->consolidatedFieldArgTypeModifiersCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\InterfaceType\HookNames::INTERFACE_TYPE_FIELD_ARG_TYPE_MODIFIERS, $this->getFieldArgTypeModifiers($fieldName, $fieldArgName), $this, $fieldName, $fieldArgName);
        return $this->consolidatedFieldArgTypeModifiersCache[$cacheKey];
    }
    /**
     * Validate the constraints for a field argument
     * @param mixed $fieldArgValue
     * @param string $fieldName
     * @param string $fieldArgName
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validateFieldArgValue($fieldName, $fieldArgName, $fieldArgValue, $astNode, $objectTypeFieldResolutionFeedbackStore) : void
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($fieldName);
        if ($schemaDefinitionResolver !== $this) {
            $schemaDefinitionResolver->validateFieldArgValue($fieldName, $fieldArgName, $fieldArgValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
        }
    }
    /**
     * Get the "schema" properties as for the fieldName
     * @param string $fieldName
     */
    public final function getFieldSchemaDefinition($fieldName) : array
    {
        // First check if the value was cached
        if (!isset($this->schemaDefinitionForFieldCache[$fieldName])) {
            $this->schemaDefinitionForFieldCache[$fieldName] = $this->doGetFieldSchemaDefinition($fieldName);
        }
        return $this->schemaDefinitionForFieldCache[$fieldName];
    }
    /**
     * Get the "schema" properties as for the fieldName
     *
     * @return array<string,mixed>
     * @param string $fieldName
     */
    protected final function doGetFieldSchemaDefinition($fieldName) : array
    {
        $fieldTypeResolver = $this->getFieldTypeResolver($fieldName);
        $fieldDescription = $this->getConsolidatedFieldDescription($fieldName) ?? $fieldTypeResolver->getTypeDescription();
        $schemaDefinition = $this->getFieldTypeSchemaDefinition(
            $fieldName,
            // This method has no "Consolidated" because it makes no sense
            $fieldTypeResolver,
            $fieldDescription,
            // This method has no "Consolidated" because it makes no sense
            $this->getFieldTypeModifiers($fieldName),
            $this->getConsolidatedFieldDeprecationMessage($fieldName)
        );
        $schemaDefinition[SchemaDefinition::EXTENSIONS] = $this->getConsolidatedFieldExtensionsSchemaDefinition($fieldName);
        if ($args = $this->getFieldArgsSchemaDefinition($fieldName)) {
            $schemaDefinition[SchemaDefinition::ARGS] = $args;
        }
        return $schemaDefinition;
    }
    /**
     * Watch out: The same extensions must be present for both
     * the ObjectType and the InterfaceType!
     *
     * @return array<string,mixed>
     * @param string $fieldName
     */
    protected function getFieldExtensionsSchemaDefinition($fieldName) : array
    {
        return [SchemaDefinition::FIELD_IS_GLOBAL => $this->isFieldGlobal($fieldName), SchemaDefinition::FIELD_IS_MUTATION => $this->isFieldAMutation($fieldName), SchemaDefinition::IS_SENSITIVE_DATA_ELEMENT => \in_array($fieldName, $this->getSensitiveFieldNames())];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return array<string,mixed>
     * @param string $fieldName
     */
    protected final function getConsolidatedFieldExtensionsSchemaDefinition($fieldName) : array
    {
        // Cache the result
        $cacheKey = $fieldName;
        if (\array_key_exists($cacheKey, $this->consolidatedFieldExtensionsCache)) {
            return $this->consolidatedFieldExtensionsCache[$cacheKey];
        }
        $this->consolidatedFieldExtensionsCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\InterfaceType\HookNames::INTERFACE_TYPE_FIELD_EXTENSIONS, $this->getFieldExtensionsSchemaDefinition($fieldName), $this, $fieldName);
        return $this->consolidatedFieldExtensionsCache[$cacheKey];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @param string $fieldName
     */
    public final function getConsolidatedFieldDescription($fieldName) : ?string
    {
        // Cache the result
        $cacheKey = $fieldName;
        if (\array_key_exists($cacheKey, $this->consolidatedFieldDescriptionCache)) {
            return $this->consolidatedFieldDescriptionCache[$cacheKey];
        }
        $this->consolidatedFieldDescriptionCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\InterfaceType\HookNames::INTERFACE_TYPE_FIELD_DESCRIPTION, $this->getFieldDescription($fieldName), $this, $fieldName);
        return $this->consolidatedFieldDescriptionCache[$cacheKey];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @param string $fieldName
     */
    public final function getConsolidatedFieldDeprecationMessage($fieldName) : ?string
    {
        // Cache the result
        $cacheKey = $fieldName;
        if (\array_key_exists($cacheKey, $this->consolidatedFieldDeprecationMessageCache)) {
            return $this->consolidatedFieldDeprecationMessageCache[$cacheKey];
        }
        $this->consolidatedFieldDeprecationMessageCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\InterfaceType\HookNames::INTERFACE_TYPE_FIELD_DEPRECATION_MESSAGE, $this->getFieldDeprecationMessage($fieldName), $this, $fieldName);
        return $this->consolidatedFieldDeprecationMessageCache[$cacheKey];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return array<string,mixed>
     * @param string $fieldName
     */
    public final function getFieldArgsSchemaDefinition($fieldName) : array
    {
        // Cache the result
        $cacheKey = $fieldName;
        if (\array_key_exists($cacheKey, $this->schemaFieldArgsCache)) {
            return $this->schemaFieldArgsCache[$cacheKey];
        }
        $schemaFieldArgs = [];
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema = $moduleConfiguration->skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema();
        $consolidatedFieldArgNameTypeResolvers = $this->getConsolidatedFieldArgNameTypeResolvers($fieldName);
        foreach ($consolidatedFieldArgNameTypeResolvers as $fieldArgName => $fieldArgInputTypeResolver) {
            /**
             * `DangerouslyNonSpecificScalar` is a special scalar type which is not coerced or validated.
             * If disabled, then do not expose the directive args of this type
             */
            if ($skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema && $fieldArgInputTypeResolver === $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver()) {
                continue;
            }
            if ($this->skipExposingFieldArgInSchema($fieldName, $fieldArgName)) {
                continue;
            }
            $fieldArgDescription = $this->getConsolidatedFieldArgDescription($fieldName, $fieldArgName) ?? $fieldArgInputTypeResolver->getTypeDescription();
            $schemaFieldArgs[$fieldArgName] = $this->getFieldOrDirectiveArgTypeSchemaDefinition($fieldArgName, $fieldArgInputTypeResolver, $fieldArgDescription, $this->getConsolidatedFieldArgDefaultValue($fieldName, $fieldArgName), $this->getConsolidatedFieldArgTypeModifiers($fieldName, $fieldArgName));
            $schemaFieldArgs[$fieldArgName][SchemaDefinition::EXTENSIONS] = $this->getConsolidatedFieldArgExtensionsSchemaDefinition($fieldName, $fieldArgName);
        }
        $this->schemaFieldArgsCache[$cacheKey] = $schemaFieldArgs;
        return $this->schemaFieldArgsCache[$cacheKey];
    }
    /**
     * @return array<string,mixed>
     * @param string $fieldName
     * @param string $fieldArgName
     */
    protected function getFieldArgExtensionsSchemaDefinition($fieldName, $fieldArgName) : array
    {
        $sensitiveFieldArgNames = $this->getConsolidatedSensitiveFieldArgNames($fieldName);
        return [SchemaDefinition::IS_SENSITIVE_DATA_ELEMENT => \in_array($fieldArgName, $sensitiveFieldArgNames)];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return array<string,mixed>
     * @param string $fieldName
     * @param string $fieldArgName
     */
    protected final function getConsolidatedFieldArgExtensionsSchemaDefinition($fieldName, $fieldArgName) : array
    {
        // Cache the result
        $cacheKey = $fieldName . '(' . $fieldArgName . ':)';
        if (\array_key_exists($cacheKey, $this->consolidatedFieldArgExtensionsCache)) {
            return $this->consolidatedFieldArgExtensionsCache[$cacheKey];
        }
        $this->consolidatedFieldArgExtensionsCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\InterfaceType\HookNames::INTERFACE_TYPE_FIELD_ARG_EXTENSIONS, $this->getFieldArgExtensionsSchemaDefinition($fieldName, $fieldArgName), $this, $fieldName, $fieldArgName);
        return $this->consolidatedFieldArgExtensionsCache[$cacheKey];
    }
    /**
     * Fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user (eg: "accessControlLists")
     * @param string $fieldName
     */
    public function skipExposingFieldInSchema($fieldName) : bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema()) {
            /**
             * If `DangerouslyNonSpecificScalar` is disabled, do not expose the field if either:
             *
             *   1. its type is `DangerouslyNonSpecificScalar`
             *   2. it has any mandatory argument of type `DangerouslyNonSpecificScalar`
             */
            $consolidatedFieldArgNames = \array_keys($this->getConsolidatedFieldArgNameTypeResolvers($fieldName));
            $consolidatedFieldArgsTypeModifiers = [];
            foreach ($consolidatedFieldArgNames as $fieldArgName) {
                $consolidatedFieldArgsTypeModifiers[$fieldArgName] = $this->getConsolidatedFieldArgTypeModifiers($fieldName, $fieldArgName);
            }
            if ($this->isDangerouslyNonSpecificScalarTypeFieldType($this->getFieldTypeResolver($fieldName), $this->getConsolidatedFieldArgNameTypeResolvers($fieldName), $consolidatedFieldArgsTypeModifiers)) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * Field args may not be directly visible in the schema
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function skipExposingFieldArgInSchema($fieldName, $fieldArgName) : bool
    {
        return \false;
    }
}

<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use Exception;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\FeedbackItemProviders\DeprecationFeedbackItemProvider;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\AbstractFieldResolver;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Resolvers\CheckDangerouslyNonSpecificScalarTypeFieldOrFieldDirectiveResolverTrait;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Resolvers\InterfaceSchemaDefinitionResolverAdapter;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrFieldDirectiveResolverTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\ComponentModel\Versioning\VersioningServiceInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractClientException;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;
abstract class AbstractObjectTypeFieldResolver extends AbstractFieldResolver implements \PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface
{
    use AttachableExtensionTrait;
    use WithVersionConstraintFieldOrFieldDirectiveResolverTrait;
    use CheckDangerouslyNonSpecificScalarTypeFieldOrFieldDirectiveResolverTrait;
    use FieldOrDirectiveSchemaDefinitionResolverTrait;
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
    protected $schemaFieldArgsCache = [];
    /** @var array<string,array<string,mixed>> */
    protected $schemaFieldArgExtensionsCache = [];
    /**
     * @var array<string,ObjectTypeFieldSchemaDefinitionResolverInterface>
     */
    protected $interfaceTypeFieldSchemaDefinitionResolverCache = [];
    /** @var SplObjectStorage<FieldInterface,FieldDataAccessorInterface> */
    protected $fieldFieldDataAccessorCache;
    /**
     * @var \PoP\LooseContracts\NameResolverInterface|null
     */
    private $nameResolver;
    /**
     * @var \PoP\ComponentModel\HelperServices\SemverHelperServiceInterface|null
     */
    private $semverHelperService;
    /**
     * @var \PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface|null
     */
    private $schemaDefinitionService;
    /**
     * @var \PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface|null
     */
    private $attachableExtensionManager;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver|null
     */
    private $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\Versioning\VersioningServiceInterface|null
     */
    private $versioningService;
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
     * @param \PoP\ComponentModel\HelperServices\SemverHelperServiceInterface $semverHelperService
     */
    public final function setSemverHelperService($semverHelperService) : void
    {
        $this->semverHelperService = $semverHelperService;
    }
    protected final function getSemverHelperService() : SemverHelperServiceInterface
    {
        /** @var SemverHelperServiceInterface */
        return $this->semverHelperService = $this->semverHelperService ?? $this->instanceManager->getInstance(SemverHelperServiceInterface::class);
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
     * @param \PoP\ComponentModel\Versioning\VersioningServiceInterface $versioningService
     */
    public final function setVersioningService($versioningService) : void
    {
        $this->versioningService = $versioningService;
    }
    protected final function getVersioningService() : VersioningServiceInterface
    {
        /** @var VersioningServiceInterface */
        return $this->versioningService = $this->versioningService ?? $this->instanceManager->getInstance(VersioningServiceInterface::class);
    }
    /**
     * @return string[]
     */
    public final function getClassesToAttachTo() : array
    {
        return $this->getObjectTypeResolverClassesToAttachTo();
    }
    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers() : array
    {
        return [];
    }
    /**
     * Implement all the fieldNames defined in the interfaces
     *
     * @return string[]
     */
    public function getFieldNamesFromInterfaces() : array
    {
        $fieldNames = [];
        foreach ($this->getImplementedInterfaceTypeFieldResolvers() as $interfaceTypeFieldResolver) {
            $fieldNames = \array_merge($fieldNames, $interfaceTypeFieldResolver->getFieldNamesToImplement());
        }
        return \array_values(\array_unique($fieldNames));
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
        $interfaceTypeResolvers = [];
        foreach ($this->getImplementedInterfaceTypeFieldResolvers() as $interfaceTypeFieldResolver) {
            // Add under class as to mimick `array_unique` for object
            foreach ($interfaceTypeFieldResolver->getPartiallyImplementedInterfaceTypeResolvers() as $partiallyImplementedInterfaceTypeResolver) {
                $interfaceTypeResolvers[\get_class($partiallyImplementedInterfaceTypeResolver)] = $partiallyImplementedInterfaceTypeResolver;
            }
        }
        return \array_values($interfaceTypeResolvers);
    }
    /**
     * Return the object implementing the schema definition for this ObjectTypeFieldResolver.
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected final function getSchemaDefinitionResolver($objectTypeResolver, $fieldName) : \PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldSchemaDefinitionResolverInterface
    {
        $fieldOrInterfaceTypeFieldSchemaDefinitionResolver = $this->doGetSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($fieldOrInterfaceTypeFieldSchemaDefinitionResolver instanceof InterfaceTypeFieldSchemaDefinitionResolverInterface) {
            // Interfaces do not receive the typeResolver, so we must bridge it
            // First check if the class is cached
            $key = $objectTypeResolver->getNamespacedTypeName() . '|' . $fieldName;
            if (isset($this->interfaceTypeFieldSchemaDefinitionResolverCache[$key])) {
                return $this->interfaceTypeFieldSchemaDefinitionResolverCache[$key];
            }
            // Create an Adapter and cache it
            $interfaceTypeFieldSchemaDefinitionResolver = $fieldOrInterfaceTypeFieldSchemaDefinitionResolver;
            $interfaceSchemaDefinitionResolverAdapterClass = $this->getInterfaceSchemaDefinitionResolverAdapterClass();
            $this->interfaceTypeFieldSchemaDefinitionResolverCache[$key] = new $interfaceSchemaDefinitionResolverAdapterClass($interfaceTypeFieldSchemaDefinitionResolver);
            return $this->interfaceTypeFieldSchemaDefinitionResolverCache[$key];
        }
        $fieldSchemaDefinitionResolver = $fieldOrInterfaceTypeFieldSchemaDefinitionResolver;
        return $fieldSchemaDefinitionResolver;
    }
    /**
     * By default, the resolver is this same object, unless function
     * `getInterfaceTypeFieldSchemaDefinitionResolver` is
     * implemented
     * @return \PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldSchemaDefinitionResolverInterface|\PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldSchemaDefinitionResolverInterface
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected function doGetSchemaDefinitionResolver($objectTypeResolver, $fieldName)
    {
        if ($interfaceTypeFieldSchemaDefinitionResolver = $this->getInterfaceTypeFieldSchemaDefinitionResolver($objectTypeResolver, $fieldName)) {
            /** @var InterfaceTypeFieldSchemaDefinitionResolverInterface */
            return $interfaceTypeFieldSchemaDefinitionResolver;
        }
        return $this;
    }
    /**
     * Retrieve the InterfaceTypeFieldSchemaDefinitionResolverInterface
     * By default, if the ObjectTypeFieldResolver implements an interface,
     * it is used as SchemaDefinitionResolver for the matching fields
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected function getInterfaceTypeFieldSchemaDefinitionResolver($objectTypeResolver, $fieldName) : ?InterfaceTypeFieldResolverInterface
    {
        foreach ($this->getImplementedInterfaceTypeFieldResolvers() as $implementedInterfaceTypeFieldResolver) {
            if (!\in_array($fieldName, $implementedInterfaceTypeFieldResolver->getFieldNamesToImplement())) {
                continue;
            }
            return $implementedInterfaceTypeFieldResolver;
        }
        return null;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
        return SchemaTypeModifiers::NONE;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldDescription($objectTypeResolver, $fieldName);
        }
        return null;
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getConsolidatedFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        // Cache the result
        $cacheKey = \get_class($objectTypeResolver) . '.' . $fieldName;
        if (\array_key_exists($cacheKey, $this->consolidatedFieldDescriptionCache)) {
            return $this->consolidatedFieldDescriptionCache[$cacheKey];
        }
        $this->consolidatedFieldDescriptionCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\ObjectType\HookNames::OBJECT_TYPE_FIELD_DESCRIPTION, $this->getFieldDescription($objectTypeResolver, $fieldName), $this, $objectTypeResolver, $fieldName);
        return $this->consolidatedFieldDescriptionCache[$cacheKey];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getConsolidatedFieldDeprecationMessage($objectTypeResolver, $fieldName) : ?string
    {
        // Cache the result
        $cacheKey = \get_class($objectTypeResolver) . '.' . $fieldName;
        if (\array_key_exists($cacheKey, $this->consolidatedFieldDeprecationMessageCache)) {
            return $this->consolidatedFieldDeprecationMessageCache[$cacheKey];
        }
        $this->consolidatedFieldDeprecationMessageCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\ObjectType\HookNames::OBJECT_TYPE_FIELD_DEPRECATION_MESSAGE, $this->getFieldDeprecationMessage($objectTypeResolver, $fieldName), $this, $objectTypeResolver, $fieldName);
        return $this->consolidatedFieldDeprecationMessageCache[$cacheKey];
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        }
        return [];
    }
    /**
     * @return string[]
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getSensitiveFieldArgNames($objectTypeResolver, $fieldName) : array
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getSensitiveFieldArgNames($objectTypeResolver, $fieldName);
        }
        return [];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName);
        }
        // Version constraint (possibly enabled)
        if ($fieldArgName === SchemaDefinition::VERSION_CONSTRAINT) {
            return $this->getVersionConstraintFieldOrDirectiveArgDescription();
        }
        return null;
    }
    /**
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName)
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
        }
        return null;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
        return SchemaTypeModifiers::NONE;
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        // Cache the result
        $cacheKey = \get_class($objectTypeResolver) . '.' . $fieldName;
        if (\array_key_exists($cacheKey, $this->consolidatedFieldArgNameTypeResolversCache)) {
            return $this->consolidatedFieldArgNameTypeResolversCache[$cacheKey];
        }
        /**
         * Allow to override/extend the inputs (eg: module "Post Categories" can add
         * input "categories" to field "Root.createPost")
         */
        $consolidatedFieldArgNameTypeResolvers = App::applyFilters(\PoP\ComponentModel\FieldResolvers\ObjectType\HookNames::OBJECT_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS, $this->getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName), $this, $objectTypeResolver, $fieldName);
        // Exclude the sensitive field args, if "Admin" Schema is not enabled
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->exposeSensitiveDataInSchema()) {
            $sensitiveFieldArgNames = $this->getConsolidatedSensitiveFieldArgNames($objectTypeResolver, $fieldName);
            $consolidatedFieldArgNameTypeResolvers = \array_filter($consolidatedFieldArgNameTypeResolvers, function (string $fieldArgName) use($sensitiveFieldArgNames) {
                return !\in_array($fieldArgName, $sensitiveFieldArgNames);
            }, \ARRAY_FILTER_USE_KEY);
        }
        /**
         * Add the version constraint (if enabled)
         * Only add the argument if this field or directive has a version
         * If it doesn't, then there will only be one version of it,
         * and it can be kept empty for simplicity
         */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->enableSemanticVersionConstraints() && $this->hasFieldVersion($objectTypeResolver, $fieldName)) {
            /**
             * The version is always of the `String` type service, but do not
             * obtain it through method `getStringScalarTypeResolver` so that
             * this method is not declared on all extending classes.
             *
             * @var StringScalarTypeResolver
             */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $consolidatedFieldArgNameTypeResolvers[SchemaDefinition::VERSION_CONSTRAINT] = $stringScalarTypeResolver;
        }
        $this->consolidatedFieldArgNameTypeResolversCache[$cacheKey] = $consolidatedFieldArgNameTypeResolvers;
        return $this->consolidatedFieldArgNameTypeResolversCache[$cacheKey];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getConsolidatedSensitiveFieldArgNames($objectTypeResolver, $fieldName) : array
    {
        // Cache the result
        $cacheKey = \get_class($objectTypeResolver) . '.' . $fieldName;
        if (\array_key_exists($cacheKey, $this->consolidatedSensitiveFieldArgNamesCache)) {
            return $this->consolidatedSensitiveFieldArgNamesCache[$cacheKey];
        }
        $this->consolidatedSensitiveFieldArgNamesCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\ObjectType\HookNames::OBJECT_TYPE_FIELD_ARG_NAME_TYPE_RESOLVERS, $this->getSensitiveFieldArgNames($objectTypeResolver, $fieldName), $this, $objectTypeResolver, $fieldName);
        return $this->consolidatedSensitiveFieldArgNamesCache[$cacheKey];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getConsolidatedFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        // Cache the result
        $cacheKey = \get_class($objectTypeResolver) . '.' . $fieldName . '(' . $fieldArgName . ':)';
        if (\array_key_exists($cacheKey, $this->consolidatedFieldArgDescriptionCache)) {
            return $this->consolidatedFieldArgDescriptionCache[$cacheKey];
        }
        $this->consolidatedFieldArgDescriptionCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\ObjectType\HookNames::OBJECT_TYPE_FIELD_ARG_DESCRIPTION, $this->getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName), $this, $objectTypeResolver, $fieldName, $fieldArgName);
        return $this->consolidatedFieldArgDescriptionCache[$cacheKey];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getConsolidatedFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName)
    {
        // Cache the result
        $cacheKey = \get_class($objectTypeResolver) . '.' . $fieldName . '(' . $fieldArgName . ':)';
        if (\array_key_exists($cacheKey, $this->consolidatedFieldArgDefaultValueCache)) {
            return $this->consolidatedFieldArgDefaultValueCache[$cacheKey];
        }
        $this->consolidatedFieldArgDefaultValueCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\ObjectType\HookNames::OBJECT_TYPE_FIELD_ARG_DEFAULT_VALUE, $this->getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName), $this, $objectTypeResolver, $fieldName, $fieldArgName);
        return $this->consolidatedFieldArgDefaultValueCache[$cacheKey];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getConsolidatedFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        // Cache the result
        $cacheKey = \get_class($objectTypeResolver) . '.' . $fieldName . '(' . $fieldArgName . ':)';
        if (\array_key_exists($cacheKey, $this->consolidatedFieldArgTypeModifiersCache)) {
            return $this->consolidatedFieldArgTypeModifiersCache[$cacheKey];
        }
        $this->consolidatedFieldArgTypeModifiersCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\ObjectType\HookNames::OBJECT_TYPE_FIELD_ARG_TYPE_MODIFIERS, $this->getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName), $this, $objectTypeResolver, $fieldName, $fieldArgName);
        return $this->consolidatedFieldArgTypeModifiersCache[$cacheKey];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return array<string,mixed>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public final function getFieldArgsSchemaDefinition($objectTypeResolver, $fieldName) : array
    {
        // Cache the result
        $cacheKey = \get_class($objectTypeResolver) . '.' . $fieldName;
        if (\array_key_exists($cacheKey, $this->schemaFieldArgsCache)) {
            return $this->schemaFieldArgsCache[$cacheKey];
        }
        $schemaFieldArgs = [];
        $consolidatedFieldArgNameTypeResolvers = $this->getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        foreach ($consolidatedFieldArgNameTypeResolvers as $fieldArgName => $fieldArgInputTypeResolver) {
            $fieldArgDescription = $this->getConsolidatedFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) ?? $fieldArgInputTypeResolver->getTypeDescription();
            $schemaFieldArgs[$fieldArgName] = $this->getFieldOrDirectiveArgTypeSchemaDefinition($fieldArgName, $fieldArgInputTypeResolver, $fieldArgDescription, $this->getConsolidatedFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName), $this->getConsolidatedFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName));
            $schemaFieldArgs[$fieldArgName][SchemaDefinition::EXTENSIONS] = $this->getConsolidatedFieldArgExtensionsSchemaDefinition($objectTypeResolver, $fieldName, $fieldArgName);
        }
        $this->schemaFieldArgsCache[$cacheKey] = $schemaFieldArgs;
        return $this->schemaFieldArgsCache[$cacheKey];
    }
    /**
     * @return array<string,mixed>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    protected function getFieldArgExtensionsSchemaDefinition($objectTypeResolver, $fieldName, $fieldArgName) : array
    {
        $sensitiveFieldArgNames = $this->getConsolidatedSensitiveFieldArgNames($objectTypeResolver, $fieldName);
        return [SchemaDefinition::IS_SENSITIVE_DATA_ELEMENT => \in_array($fieldArgName, $sensitiveFieldArgNames)];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return array<string,mixed>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    protected final function getConsolidatedFieldArgExtensionsSchemaDefinition($objectTypeResolver, $fieldName, $fieldArgName) : array
    {
        // Cache the result
        $cacheKey = \get_class($objectTypeResolver) . '.' . $fieldName . '(' . $fieldArgName . ':)';
        if (\array_key_exists($cacheKey, $this->schemaFieldArgExtensionsCache)) {
            return $this->schemaFieldArgExtensionsCache[$cacheKey];
        }
        $this->schemaFieldArgExtensionsCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\ObjectType\HookNames::OBJECT_TYPE_FIELD_ARG_EXTENSIONS, $this->getFieldArgExtensionsSchemaDefinition($objectTypeResolver, $fieldName, $fieldArgName), $this, $objectTypeResolver, $fieldName, $fieldArgName);
        return $this->schemaFieldArgExtensionsCache[$cacheKey];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDeprecationMessage($objectTypeResolver, $fieldName) : ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldDeprecationMessage($objectTypeResolver, $fieldName);
        }
        return null;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
        return $this->getSchemaDefinitionService()->getDefaultConcreteTypeResolver();
    }
    /**
     * Validate the constraints for a field argument
     * @param mixed $fieldArgValue
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validateFieldArgValue($objectTypeResolver, $fieldName, $fieldArgName, $fieldArgValue, $astNode, $objectTypeFieldResolutionFeedbackStore) : void
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($objectTypeResolver, $fieldName);
        if ($schemaDefinitionResolver !== $this) {
            $schemaDefinitionResolver->validateFieldArgValue($objectTypeResolver, $fieldName, $fieldArgName, $fieldArgValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function isGlobal($objectTypeResolver, $fieldName) : bool
    {
        return \false;
    }
    /**
     * Indicates if the fieldResolver can process this combination of fieldName and fieldArgs
     * It is required to support a multiverse of fields: different fieldResolvers can resolve the field, based on the required version (passed through $fieldDataAccessor->getValue('branch'))
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function resolveCanProcessField($objectTypeResolver, $field) : bool
    {
        /** Check if to validate the version */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->enableSemanticVersionConstraints() && $this->hasFieldVersion($objectTypeResolver, $field->getName())) {
            /**
             * Please notice: we can get the fieldVersion directly from this instance,
             * and not from the schemaDefinition, because the version is set at
             * the ObjectTypeFieldResolver level, and not the InterfaceTypeFieldResolver,
             * which is the other entity filling data inside the schemaDefinition object.
             *
             * If this field is tagged with a version...
             *
             * @var string
             */
            $schemaFieldVersion = $this->getFieldVersion($objectTypeResolver, $field->getName());
            /**
             * Get versionConstraint in this order:
             * 1. Passed as field argument
             * 2. Through param `fieldVersionConstraints[$field->getName()]`: specific to the namespaced type + field
             * 3. Through param `fieldVersionConstraints[$field->getName()]`: specific to the type + field
             * 4. Through param `versionConstraint`: applies to all fields and directives in the query
             */
            $versionConstraint = $field->getArgumentValue(SchemaDefinition::VERSION_CONSTRAINT) ?? $this->getVersioningService()->getVersionConstraintsForField($objectTypeResolver, $field) ?? App::getState('version-constraint');
            /**
             * If the query doesn't restrict the version, then do not process
             */
            if (!$versionConstraint) {
                return \false;
            }
            /**
             * Compare using semantic versioning constraint rules, as used by Composer
             */
            return $this->getSemverHelperService()->satisfies($schemaFieldVersion, $versionConstraint);
        }
        return \true;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function validateResolvedFieldType($objectTypeResolver, $field) : bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->validateFieldTypeResponseWithSchemaDefinition();
    }
    /**
     * Custom validations
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validateFieldKeyValues($objectTypeResolver, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function collectFieldValidationDeprecationMessages($objectTypeResolver, $field, $objectTypeFieldResolutionFeedbackStore) : void
    {
        $fieldDeprecationMessage = $this->getConsolidatedFieldDeprecationMessage($objectTypeResolver, $field->getName());
        if ($fieldDeprecationMessage !== null) {
            $objectTypeFieldResolutionFeedbackStore->addDeprecation(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(DeprecationFeedbackItemProvider::class, DeprecationFeedbackItemProvider::D1, [$field->getName(), $fieldDeprecationMessage]), $field));
        }
    }
    /**
     * Fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user (eg: "accessControlLists")
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function skipExposingFieldInSchema($objectTypeResolver, $fieldName) : bool
    {
        /**
         * Do not expose the versioned fields via introspection
         */
        if ($this->hasFieldVersion($objectTypeResolver, $fieldName)) {
            return \true;
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema()) {
            /**
             * If `DangerouslyNonSpecificScalar` is disabled, do not expose the field if either:
             *
             *   1. its type is `DangerouslyNonSpecificScalar`
             *   2. it has any mandatory argument of type `DangerouslyNonSpecificScalar`
             */
            $consolidatedFieldArgNames = \array_keys($this->getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $fieldName));
            $consolidatedFieldArgsTypeModifiers = [];
            foreach ($consolidatedFieldArgNames as $fieldArgName) {
                $consolidatedFieldArgsTypeModifiers[$fieldArgName] = $this->getConsolidatedFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
            }
            if ($this->isDangerouslyNonSpecificScalarTypeFieldType($this->getFieldTypeResolver($objectTypeResolver, $fieldName), $this->getConsolidatedFieldArgNameTypeResolvers($objectTypeResolver, $fieldName), $consolidatedFieldArgsTypeModifiers)) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * Field args may not be directly visible in the schema
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function skipExposingFieldArgInSchema($objectTypeResolver, $fieldName, $fieldArgName) : bool
    {
        return \false;
    }
    /**
     * Get the "schema" properties as for the fieldName
     *
     * @return array<string,mixed>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public final function getFieldSchemaDefinition($objectTypeResolver, $fieldName) : array
    {
        // First check if the value was cached
        $key = $objectTypeResolver->getNamespacedTypeName() . '|' . $fieldName;
        if (!isset($this->schemaDefinitionForFieldCache[$key])) {
            $this->schemaDefinitionForFieldCache[$key] = $this->doGetFieldSchemaDefinition($objectTypeResolver, $fieldName);
        }
        return $this->schemaDefinitionForFieldCache[$key];
    }
    /**
     * Get the "schema" properties as for the fieldName
     *
     * @return array<string,mixed>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected final function doGetFieldSchemaDefinition($objectTypeResolver, $fieldName) : array
    {
        $fieldTypeResolver = $this->getFieldTypeResolver($objectTypeResolver, $fieldName);
        $fieldDescription = $this->getConsolidatedFieldDescription($objectTypeResolver, $fieldName) ?? $fieldTypeResolver->getTypeDescription();
        $schemaDefinition = $this->getFieldTypeSchemaDefinition(
            $fieldName,
            // This method has no "Consolidated" because it makes no sense
            $fieldTypeResolver,
            $fieldDescription,
            // This method has no "Consolidated" because it makes no sense
            $this->getFieldTypeModifiers($objectTypeResolver, $fieldName),
            $this->getConsolidatedFieldDeprecationMessage($objectTypeResolver, $fieldName)
        );
        if ($args = $this->getFieldArgsSchemaDefinition($objectTypeResolver, $fieldName)) {
            $schemaDefinition[SchemaDefinition::ARGS] = $args;
            // Check it args can be queried without their name
            if ($this->enableOrderedSchemaFieldArgs($objectTypeResolver, $fieldName)) {
                $schemaDefinition[SchemaDefinition::ORDERED_ARGS_ENABLED] = \true;
            }
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->enableSemanticVersionConstraints() && $this->hasFieldVersion($objectTypeResolver, $fieldName)) {
            $schemaDefinition[SchemaDefinition::VERSION] = $this->getFieldVersion($objectTypeResolver, $fieldName);
        }
        $schemaDefinition[SchemaDefinition::EXTENSIONS] = $this->getConsolidatedFieldExtensionsSchemaDefinition($objectTypeResolver, $fieldName);
        return $schemaDefinition;
    }
    /**
     * Watch out: The same extensions must be present for both
     * the ObjectType and the InterfaceType!
     *
     * @return array<string,mixed>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected function getFieldExtensionsSchemaDefinition($objectTypeResolver, $fieldName) : array
    {
        return [SchemaDefinition::FIELD_IS_GLOBAL => $this->isGlobal($objectTypeResolver, $fieldName), SchemaDefinition::FIELD_IS_MUTATION => $this->getFieldMutationResolver($objectTypeResolver, $fieldName) !== null, SchemaDefinition::IS_SENSITIVE_DATA_ELEMENT => \in_array($fieldName, $this->getSensitiveFieldNames())];
    }
    /**
     * Consolidation of the schema field arguments. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return array<string,mixed>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected final function getConsolidatedFieldExtensionsSchemaDefinition($objectTypeResolver, $fieldName) : array
    {
        // Cache the result
        $cacheKey = \get_class($objectTypeResolver) . '.' . $fieldName;
        if (\array_key_exists($cacheKey, $this->consolidatedFieldExtensionsCache)) {
            return $this->consolidatedFieldExtensionsCache[$cacheKey];
        }
        $this->consolidatedFieldExtensionsCache[$cacheKey] = App::applyFilters(\PoP\ComponentModel\FieldResolvers\ObjectType\HookNames::OBJECT_TYPE_FIELD_EXTENSIONS, $this->getFieldExtensionsSchemaDefinition($objectTypeResolver, $fieldName), $this, $objectTypeResolver, $fieldName);
        return $this->consolidatedFieldExtensionsCache[$cacheKey];
    }
    /**
     * @return class-string<InterfaceSchemaDefinitionResolverAdapter>
     */
    protected function getInterfaceSchemaDefinitionResolverAdapterClass() : string
    {
        return InterfaceSchemaDefinitionResolverAdapter::class;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function enableOrderedSchemaFieldArgs($objectTypeResolver, $fieldName) : bool
    {
        return \true;
    }
    /**
     * Please notice: the version always comes from the fieldResolver, and not from the schemaDefinitionResolver
     * That is because it is the implementer the one who knows what version it is, and not the one defining the interface
     * If the interface changes, the implementer will need to change, so the version will be upgraded
     * But it could also be that the contract doesn't change, but the implementation changes
     * In particular, Interfaces are schemaDefinitionResolver, but they must not indicate the version...
     * it's really not their responsibility
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldVersion($objectTypeResolver, $fieldName) : ?string
    {
        return null;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public final function hasFieldVersion($objectTypeResolver, $fieldName) : bool
    {
        return !empty($this->getFieldVersion($objectTypeResolver, $fieldName));
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    protected function addValueResolutionFeedback($objectTypeResolver, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
    }
    /**
     * @return CheckpointInterface[]
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    public function getValidationCheckpoints($objectTypeResolver, $fieldDataAccessor, $object) : array
    {
        return [];
    }
    /**
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validateFieldArgsForObject($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
    }
    /**
     * Indicate: if the field has a single field argument, which is of type InputObject,
     * then retrieve the value for its input fields?
     *
     * By default, that's the case with mutations, as they pass a single input
     * under name "input".
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function extractInputObjectFieldForMutation($objectTypeResolver, $fieldName) : bool
    {
        $mutationResolver = $this->getFieldMutationResolver($objectTypeResolver, $fieldName);
        return $mutationResolver !== null;
    }
    /**
     * If the field has a single argument, which is of type InputObject,
     * then retrieve the value for its input fields.
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function getFieldArgsInputObjectSubpropertyName($objectTypeResolver, $field) : ?string
    {
        $fieldArgNameTypeResolvers = $this->getFieldArgNameTypeResolvers($objectTypeResolver, $field->getName());
        // Check if there is only one fieldArg
        if (\count($fieldArgNameTypeResolvers) !== 1) {
            return null;
        }
        // Check if the fieldArg is an InputObject
        $fieldArgName = \key($fieldArgNameTypeResolvers);
        $fieldArgTypeResolver = $fieldArgNameTypeResolvers[$fieldArgName];
        if (!$fieldArgTypeResolver instanceof InputObjectTypeResolverInterface) {
            return null;
        }
        return $fieldArgName;
    }
    /**
     * The mutation can be validated either on the schema (`false`)
     * or on the object (`true`)
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function validateMutationOnObject($objectTypeResolver, $fieldName) : bool
    {
        return \false;
    }
    /**
     * @return mixed
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        $this->addValueResolutionFeedback($objectTypeResolver, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        // If a MutationResolver is declared, let it resolve the value
        $mutationResolver = $this->getFieldMutationResolver($objectTypeResolver, $fieldDataAccessor->getFieldName());
        if ($mutationResolver !== null) {
            return $this->executeMutation($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        }
        // Base case: If the field->getName() exists as property in the object, then retrieve it
        if (\property_exists($object, $fieldDataAccessor->getFieldName())) {
            return $object->{$fieldDataAccessor->getFieldName()};
        }
        return null;
    }
    /**
     * @return mixed
     * @param object $object
     */
    private function executeMutation(ObjectTypeResolverInterface $objectTypeResolver, $object, FieldDataAccessorInterface $fieldDataAccessor, ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore)
    {
        /** @var MutationResolverInterface */
        $mutationResolver = $this->getFieldMutationResolver($objectTypeResolver, $fieldDataAccessor->getFieldName());
        try {
            if ($this->validateMutationOnObject($objectTypeResolver, $fieldDataAccessor->getFieldName())) {
                $fieldArgsForMutationForObject = $this->prepareFieldArgsForMutationForObject($fieldDataAccessor->getFieldArgs(), $objectTypeResolver, $fieldDataAccessor->getField(), $object);
                $fieldDataAccessor = $objectTypeResolver->createFieldDataAccessor($fieldDataAccessor->getField(), $fieldArgsForMutationForObject);
            }
            $fieldDataAccessorForMutation = $objectTypeResolver->getFieldDataAccessorForMutation($fieldDataAccessor);
            return $mutationResolver->executeMutation($fieldDataAccessorForMutation, $objectTypeFieldResolutionFeedbackStore);
        } catch (Exception $e) {
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            if ($moduleConfiguration->logExceptionErrorMessagesAndTraces()) {
                $objectTypeFieldResolutionFeedbackStore->addLog(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(ErrorFeedbackItemProvider::class, ErrorFeedbackItemProvider::E6A, [$fieldDataAccessor->getFieldName(), $e->getMessage(), $e->getTraceAsString()]), $fieldDataAccessor->getField()));
            }
            $sendExceptionToClient = $e instanceof AbstractClientException || $moduleConfiguration->sendExceptionErrorMessages();
            $feedbackItemResolution = $sendExceptionToClient ? $moduleConfiguration->sendExceptionTraces() ? new FeedbackItemResolution(ErrorFeedbackItemProvider::class, ErrorFeedbackItemProvider::E6A, [$fieldDataAccessor->getFieldName(), $e->getMessage(), $e->getTraceAsString()]) : new FeedbackItemResolution(ErrorFeedbackItemProvider::class, ErrorFeedbackItemProvider::E6, [$fieldDataAccessor->getFieldName(), $e->getMessage()]) : new FeedbackItemResolution(ErrorFeedbackItemProvider::class, ErrorFeedbackItemProvider::E7, [$fieldDataAccessor->getFieldName()]);
            $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback($feedbackItemResolution, $fieldDataAccessor->getField()));
            return null;
        }
    }
    /**
     * @param array<string,mixed> $fieldArgsForObject
     * @return array<string,mixed>
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function prepareFieldArgsForObject($fieldArgsForObject, $objectTypeResolver, $field, $object) : array
    {
        return $fieldArgsForObject;
    }
    /**
     * This method is executed AFTER the casting of the fieldArgs
     * has taken place! Then, it can further add elements to the
     * input which are not in the Schema definition of the input.
     *
     * It's use is with nested mutations, as to set the missing
     * "id" value that comes from the object, and is not provided
     * via an input to the mutation.
     *
     * @param array<string,mixed> $fieldArgsForMutationForObject
     * @return array<string,mixed>
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function prepareFieldArgsForMutationForObject($fieldArgsForMutationForObject, $objectTypeResolver, $field, $object) : array
    {
        return $fieldArgsForMutationForObject;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldMutationResolver($objectTypeResolver, $fieldName) : ?MutationResolverInterface
    {
        return null;
    }
    /**
     * Apply customizations to the field data
     *
     * @param array<string,mixed> $fieldArgs
     * @return array<string,mixed>|null null in case of validation error
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function prepareFieldArgs($fieldArgs, $objectTypeResolver, $field, $objectTypeFieldResolutionFeedbackStore) : ?array
    {
        return $fieldArgs;
    }
}

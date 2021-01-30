<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\API\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\Environment;
use PoP\ComponentModel\State\ApplicationState;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use GraphQLByPoP\GraphQLServer\ObjectModels\Directive;
use GraphQLByPoP\GraphQLServer\ObjectModels\ScalarType;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\Facades\Schema\SchemaDefinitionServiceFacade;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLByPoP\GraphQLServer\Facades\Schema\GraphQLSchemaDefinitionServiceFacade;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition as GraphQLServerSchemaDefinition;
use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;
class Schema
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var ScalarType[]
     */
    protected $types;
    /**
     * @var Directive[]
     */
    protected $directives;
    /**
     * @var \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType|null
     */
    protected $queryType = null;
    /**
     * @var \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType|null
     */
    protected $mutationType = null;
    /**
     * @var \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType|null
     */
    protected $subscriptionType = null;
    public function __construct(array $fullSchemaDefinition, string $id)
    {
        $this->id = $id;
        // Initialize the global elements before anything, since they will
        // be references from the ObjectType: Fields/Connections/Directives
        // 1. Initialize all the Scalar types
        $scalarTypeNames = [\GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_ID, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_STRING, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_INT, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_FLOAT, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_BOOL, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_OBJECT, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_MIXED, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_DATE, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_TIME, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_URL, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_EMAIL, \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinition::TYPE_IP];
        $this->types = [];
        foreach ($scalarTypeNames as $typeName) {
            $typeSchemaDefinitionPath = [\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES, $typeName];
            $this->types[] = new \GraphQLByPoP\GraphQLServer\ObjectModels\ScalarType($fullSchemaDefinition, $typeSchemaDefinitionPath, $typeName);
        }
        // Enable or not to add the global fields to the schema, since they may pollute the documentation
        if (\GraphQLByPoP\GraphQLServer\Environment::addGlobalFieldsToSchema()) {
            // Add the fields in the registry
            // 1. Global fields
            \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers::initFieldsFromPath($fullSchemaDefinition, [\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_FIELDS]);
            // 2. Global connections
            \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers::initFieldsFromPath($fullSchemaDefinition, [\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_CONNECTIONS]);
        }
        // Initialize the interfaces
        $interfaceSchemaDefinitionPath = [\PoP\API\Schema\SchemaDefinition::ARGNAME_INTERFACES];
        $interfaceSchemaDefinitionPointer = \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers::advancePointerToPath($fullSchemaDefinition, $interfaceSchemaDefinitionPath);
        foreach (\array_keys($interfaceSchemaDefinitionPointer) as $interfaceName) {
            new \GraphQLByPoP\GraphQLServer\ObjectModels\InterfaceType($fullSchemaDefinition, \array_merge($interfaceSchemaDefinitionPath, [$interfaceName]));
        }
        // Initialize the directives
        $this->directives = [];
        foreach ($fullSchemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES] as $directiveName => $directiveDefinition) {
            $directiveSchemaDefinitionPath = [\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES, $directiveName];
            $this->directives[] = $this->getDirectiveInstance($fullSchemaDefinition, $directiveSchemaDefinitionPath);
        }
        $graphQLSchemaDefinitionService = \GraphQLByPoP\GraphQLServer\Facades\Schema\GraphQLSchemaDefinitionServiceFacade::getInstance();
        // Initialize the different types
        // 1. queryType
        $queryTypeSchemaKey = $graphQLSchemaDefinitionService->getQueryRootTypeSchemaKey();
        $queryTypeSchemaDefinitionPath = [\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES, $queryTypeSchemaKey];
        $this->queryType = $this->getTypeInstance($fullSchemaDefinition, $queryTypeSchemaDefinitionPath);
        // 2. mutationType
        if ($mutationTypeSchemaKey = $graphQLSchemaDefinitionService->getMutationRootTypeSchemaKey()) {
            $mutationTypeSchemaDefinitionPath = [\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES, $mutationTypeSchemaKey];
            $this->mutationType = $this->getTypeInstance($fullSchemaDefinition, $mutationTypeSchemaDefinitionPath);
        }
        // 3. subscriptionType
        if ($subscriptionTypeSchemaKey = $graphQLSchemaDefinitionService->getSubscriptionRootTypeSchemaKey()) {
            $subscriptionTypeSchemaDefinitionPath = [\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES, $subscriptionTypeSchemaKey];
            $this->subscriptionType = $this->getTypeInstance($fullSchemaDefinition, $subscriptionTypeSchemaDefinitionPath);
        }
        // 2. Initialize the Object and Union types from under "types" and the Interface type from under "interfaces"
        $resolvableTypes = [];
        $resolvableTypeSchemaKeys = \array_diff(\array_keys($fullSchemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES]), $scalarTypeNames);
        foreach ($resolvableTypeSchemaKeys as $typeName) {
            $typeSchemaDefinitionPath = [\PoP\API\Schema\SchemaDefinition::ARGNAME_TYPES, $typeName];
            $resolvableTypes[] = $this->getTypeInstance($fullSchemaDefinition, $typeSchemaDefinitionPath);
        }
        $interfaceNames = \array_keys($fullSchemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_INTERFACES]);
        // Now we can sort the interfaces, after creating new `InterfaceType`
        // Everything else was already sorted in `SchemaDefinitionReferenceRegistry`
        // Sort the elements in the schema alphabetically
        if (\GraphQLByPoP\GraphQLServer\ComponentConfiguration::sortSchemaAlphabetically()) {
            \sort($interfaceNames);
        }
        foreach ($interfaceNames as $interfaceName) {
            $interfaceSchemaDefinitionPath = [\PoP\API\Schema\SchemaDefinition::ARGNAME_INTERFACES, $interfaceName];
            $resolvableTypes[] = new \GraphQLByPoP\GraphQLServer\ObjectModels\InterfaceType($fullSchemaDefinition, $interfaceSchemaDefinitionPath);
        }
        // 3. Since all types have been initialized by now, we tell them to further initialize their type dependencies, since now they all exist
        // This step will initialize the dynamic Enum and InputObject types and add them to the registry
        foreach ($resolvableTypes as $resolvableType) {
            $resolvableType->initializeTypeDependencies();
        }
        /**
         * If nested mutations are disabled, we will use types QueryRoot and MutationRoot,
         * and the data for type "Root" can be safely not sent
         */
        $vars = \PoP\ComponentModel\State\ApplicationState::getVars();
        if (!$vars['nested-mutations-enabled']) {
            $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
            $schemaDefinitionService = \PoP\Engine\Facades\Schema\SchemaDefinitionServiceFacade::getInstance();
            $rootTypeResolverClass = $schemaDefinitionService->getRootTypeResolverClass();
            /** @var TypeResolverInterface */
            $rootTypeResolver = $instanceManager->getInstance($rootTypeResolverClass);
            $resolvableTypes = \array_filter($resolvableTypes, function (\GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType $objectType) use($rootTypeResolver) {
                return $objectType->getName() != $rootTypeResolver->getTypeName();
            });
        }
        // 4. Add the Object, Union and Interface types under $resolvableTypes, and the dynamic Enum and InputObject types from the registry
        $schemaDefinitionReferenceRegistry = \GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade::getInstance();
        $this->types = \array_merge($this->types, $resolvableTypes, $schemaDefinitionReferenceRegistry->getDynamicTypes());
    }
    protected function getTypeInstance(array &$fullSchemaDefinition, array $typeSchemaDefinitionPath)
    {
        $typeSchemaDefinitionPointer =& $fullSchemaDefinition;
        foreach ($typeSchemaDefinitionPath as $pathLevel) {
            $typeSchemaDefinitionPointer =& $typeSchemaDefinitionPointer[$pathLevel];
        }
        $typeSchemaDefinition = $typeSchemaDefinitionPointer;
        // The type here can either be an ObjectType or a UnionType
        return $typeSchemaDefinition[\PoP\API\Schema\SchemaDefinition::ARGNAME_IS_UNION] ?? null ? new \GraphQLByPoP\GraphQLServer\ObjectModels\UnionType($fullSchemaDefinition, $typeSchemaDefinitionPath) : new \GraphQLByPoP\GraphQLServer\ObjectModels\ObjectType($fullSchemaDefinition, $typeSchemaDefinitionPath);
    }
    protected function getDirectiveInstance(array &$fullSchemaDefinition, array $directiveSchemaDefinitionPath) : \GraphQLByPoP\GraphQLServer\ObjectModels\Directive
    {
        return new \GraphQLByPoP\GraphQLServer\ObjectModels\Directive($fullSchemaDefinition, $directiveSchemaDefinitionPath);
    }
    public function getID()
    {
        return $this->id;
    }
    public function getQueryType() : \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType
    {
        return $this->queryType;
    }
    public function getQueryTypeID() : string
    {
        return $this->getQueryType()->getID();
    }
    public function getMutationType() : ?\GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType
    {
        return $this->mutationType;
    }
    public function getMutationTypeID() : ?string
    {
        if ($mutationType = $this->getMutationType()) {
            return $mutationType->getID();
        }
        return null;
    }
    public function getSubscriptionType() : ?\GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType
    {
        return $this->subscriptionType;
    }
    public function getSubscriptionTypeID() : ?string
    {
        if ($subscriptionType = $this->getSubscriptionType()) {
            return $subscriptionType->getID();
        }
        return null;
    }
    public function getTypes()
    {
        return $this->types;
    }
    public function getTypeIDs() : array
    {
        return \array_map(function (\GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType $type) {
            return $type->getID();
        }, $this->getTypes());
    }
    public function getDirectives()
    {
        return $this->directives;
    }
    public function getDirectiveIDs() : array
    {
        return \array_map(function (\GraphQLByPoP\GraphQLServer\ObjectModels\Directive $directive) {
            return $directive->getID();
        }, $this->getDirectives());
    }
    public function getType(string $typeName) : ?\GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType
    {
        // If the provided typeName contains the namespace separator, then compare by qualifiedType
        $useQualifiedName = \strpos($typeName, \PoP\API\Schema\SchemaDefinition::TOKEN_NAMESPACE_SEPARATOR) !== \false;
        // From all the types, get the one that has this name
        foreach ($this->types as $type) {
            // The provided `$typeName` can include namespaces or not
            $nameMatches = $useQualifiedName ? $typeName == $type->getNamespacedName() : $typeName == $type->getElementName();
            if ($nameMatches) {
                return $type;
            }
        }
        return null;
    }
    public function getTypeID(string $typeName) : ?string
    {
        if ($type = $this->getType($typeName)) {
            return $type->getID();
        }
        return null;
    }
}

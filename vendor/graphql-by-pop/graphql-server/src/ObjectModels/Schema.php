<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use GraphQLByPoP\GraphQLServer\Facades\Schema\GraphQLSchemaDefinitionServiceFacade;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoPAPI\API\Schema\SchemaDefinition;
use PoPAPI\API\Schema\TypeKinds;
use PoP\Root\Services\StandaloneServiceTrait;
use PoP\ComponentModel\Schema\SchemaDefinitionTokens;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Exception\ImpossibleToHappenException;
class Schema
{
    use StandaloneServiceTrait;
    /** @var NamedTypeInterface[] */
    protected $types;
    /** @var Directive[] */
    protected $directives;
    /**
     * @var \GraphQLByPoP\GraphQLServer\ObjectModels\SchemaExtensions
     */
    protected $schemaExtensions;
    /**
     * @var string
     */
    protected $id;
    /**
     * @param array<string,mixed> $fullSchemaDefinition
     */
    public function __construct(array &$fullSchemaDefinition, string $id)
    {
        $this->id = $id;
        // Enable or not to add the global fields to the schema, since they may pollute the documentation
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->exposeGlobalFieldsInGraphQLSchema()) {
            // Add the global fields in the registry
            SchemaDefinitionHelpers::createFieldsFromPath($fullSchemaDefinition, [SchemaDefinition::GLOBAL_FIELDS]);
        }
        // Initialize the directives
        $this->directives = [];
        /** @var string $directiveName */
        foreach (\array_keys($fullSchemaDefinition[SchemaDefinition::GLOBAL_DIRECTIVES]) as $directiveName) {
            $this->directives[] = $this->getDirectiveInstance($fullSchemaDefinition, $directiveName);
        }
        // Initialize the types
        $this->types = [];
        /** @var string $typeKind */
        foreach ($fullSchemaDefinition[SchemaDefinition::TYPES] as $typeKind => $typeSchemaDefinitions) {
            /** @var string $typeName */
            foreach (\array_keys($typeSchemaDefinitions) as $typeName) {
                $this->types[] = $this->getTypeInstance($fullSchemaDefinition, $typeKind, $typeName);
            }
        }
        $schemaExtensionsSchemaDefinitionPath = [SchemaDefinition::EXTENSIONS];
        $this->schemaExtensions = new \GraphQLByPoP\GraphQLServer\ObjectModels\SchemaExtensions($fullSchemaDefinition, $schemaExtensionsSchemaDefinitionPath);
    }
    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string $typeKind
     * @param string $typeName
     */
    protected function getTypeInstance(&$fullSchemaDefinition, $typeKind, $typeName) : \GraphQLByPoP\GraphQLServer\ObjectModels\NamedTypeInterface
    {
        $typeSchemaDefinitionPath = [SchemaDefinition::TYPES, $typeKind, $typeName];
        switch ($typeKind) {
            case TypeKinds::OBJECT:
                return new \GraphQLByPoP\GraphQLServer\ObjectModels\ObjectType($fullSchemaDefinition, $typeSchemaDefinitionPath);
            case TypeKinds::INTERFACE:
                return new \GraphQLByPoP\GraphQLServer\ObjectModels\InterfaceType($fullSchemaDefinition, $typeSchemaDefinitionPath);
            case TypeKinds::UNION:
                return new \GraphQLByPoP\GraphQLServer\ObjectModels\UnionType($fullSchemaDefinition, $typeSchemaDefinitionPath);
            case TypeKinds::SCALAR:
                return new \GraphQLByPoP\GraphQLServer\ObjectModels\ScalarType($fullSchemaDefinition, $typeSchemaDefinitionPath);
            case TypeKinds::ENUM:
                return new \GraphQLByPoP\GraphQLServer\ObjectModels\EnumType($fullSchemaDefinition, $typeSchemaDefinitionPath);
            case TypeKinds::INPUT_OBJECT:
                return new \GraphQLByPoP\GraphQLServer\ObjectModels\InputObjectType($fullSchemaDefinition, $typeSchemaDefinitionPath);
            default:
                throw new ImpossibleToHappenException(\sprintf($this->__('Unknown type kind \'%s\'', 'graphql-server'), $typeKind));
        }
    }
    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string $directiveName
     */
    protected function getDirectiveInstance(&$fullSchemaDefinition, $directiveName) : \GraphQLByPoP\GraphQLServer\ObjectModels\Directive
    {
        $directiveSchemaDefinitionPath = [SchemaDefinition::GLOBAL_DIRECTIVES, $directiveName];
        return new \GraphQLByPoP\GraphQLServer\ObjectModels\Directive($fullSchemaDefinition, $directiveSchemaDefinitionPath);
    }
    public function getID() : string
    {
        return $this->id;
    }
    public function getQueryRootObjectTypeID() : string
    {
        $graphQLSchemaDefinitionService = GraphQLSchemaDefinitionServiceFacade::getInstance();
        return $this->getObjectTypeID($graphQLSchemaDefinitionService->getSchemaQueryRootObjectTypeResolver());
    }
    public function getMutationRootObjectTypeID() : ?string
    {
        $graphQLSchemaDefinitionService = GraphQLSchemaDefinitionServiceFacade::getInstance();
        if ($mutationRootTypeResolver = $graphQLSchemaDefinitionService->getSchemaMutationRootObjectTypeResolver()) {
            return $this->getObjectTypeID($mutationRootTypeResolver);
        }
        return null;
    }
    public function getSubscriptionRootObjectTypeID() : ?string
    {
        $graphQLSchemaDefinitionService = GraphQLSchemaDefinitionServiceFacade::getInstance();
        if ($subscriptionRootTypeResolver = $graphQLSchemaDefinitionService->getSchemaSubscriptionRootTypeResolver()) {
            return $this->getObjectTypeID($subscriptionRootTypeResolver);
        }
        return null;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     */
    protected final function getObjectTypeID($objectTypeResolver) : string
    {
        return SchemaDefinitionHelpers::getSchemaDefinitionReferenceObjectID([SchemaDefinition::TYPES, TypeKinds::OBJECT, $objectTypeResolver->getMaybeNamespacedTypeName()]);
    }
    /**
     * @return string[]
     */
    public function getTypeIDs() : array
    {
        return \array_map(function (\GraphQLByPoP\GraphQLServer\ObjectModels\NamedTypeInterface $type) {
            return $type->getID();
        }, $this->types);
    }
    /**
     * @return string[]
     */
    public function getDirectiveIDs() : array
    {
        return \array_map(function (\GraphQLByPoP\GraphQLServer\ObjectModels\Directive $directive) {
            return $directive->getID();
        }, $this->directives);
    }
    /**
     * @param string $typeName
     */
    public function getType($typeName) : ?\GraphQLByPoP\GraphQLServer\ObjectModels\NamedTypeInterface
    {
        // If the provided typeName contains the namespace separator, then compare by qualifiedType
        $useQualifiedName = \strpos($typeName, SchemaDefinitionTokens::NAMESPACE_SEPARATOR) !== \false;
        // From all the types, get the one that has this name
        foreach ($this->types as $type) {
            // The provided `$typeName` can include namespaces or not
            $nameMatches = $useQualifiedName ? $typeName === $type->getNamespacedName() : $typeName === $type->getElementName();
            if ($nameMatches) {
                return $type;
            }
        }
        return null;
    }
    /**
     * @param string $typeName
     */
    public function getTypeID($typeName) : ?string
    {
        if ($type = $this->getType($typeName)) {
            return $type->getID();
        }
        return null;
    }
    public function getExtensions() : \GraphQLByPoP\GraphQLServer\ObjectModels\SchemaExtensions
    {
        return $this->schemaExtensions;
    }
}

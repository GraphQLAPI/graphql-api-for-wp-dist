<?php

declare (strict_types=1);
namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoP\Root\App;
use PoPAPI\API\Schema\SchemaDefinition;
use PoPAPI\API\Schema\SchemaDefinitionHelpers;
use PoPAPI\API\Schema\TypeKinds;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class UnionTypeSchemaDefinitionProvider extends \PoPAPI\API\ObjectModels\SchemaDefinition\AbstractNamedTypeSchemaDefinitionProvider
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface
     */
    protected $unionTypeResolver;
    public function __construct(UnionTypeResolverInterface $unionTypeResolver)
    {
        $this->unionTypeResolver = $unionTypeResolver;
        parent::__construct($unionTypeResolver);
    }
    public function getTypeKind() : string
    {
        return TypeKinds::UNION;
    }
    /**
     * @return array<string,mixed>
     */
    public function getSchemaDefinition() : array
    {
        $schemaDefinition = parent::getSchemaDefinition();
        $this->addPossibleTypeSchemaDefinitions($schemaDefinition);
        $this->addInterfaceSchemaDefinitions($schemaDefinition);
        $this->addDirectiveSchemaDefinitions($schemaDefinition, \false);
        return $schemaDefinition;
    }
    /**
     * @param array<string,mixed> $schemaDefinition
     */
    protected final function addPossibleTypeSchemaDefinitions(&$schemaDefinition) : void
    {
        // Iterate through the typeResolvers from all the pickers and get their schema definitions
        $schemaDefinition[SchemaDefinition::POSSIBLE_TYPES] = [];
        foreach ($this->unionTypeResolver->getObjectTypeResolverPickers() as $picker) {
            $pickerObjectTypeResolver = $picker->getObjectTypeResolver();
            $pickerObjectTypeName = $pickerObjectTypeResolver->getMaybeNamespacedTypeName();
            $pickerObjectTypeSchemaDefinition = [SchemaDefinition::TYPE_RESOLVER => $pickerObjectTypeResolver];
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($pickerObjectTypeSchemaDefinition);
            $schemaDefinition[SchemaDefinition::POSSIBLE_TYPES][$pickerObjectTypeName] = $pickerObjectTypeSchemaDefinition;
            $this->accessedTypeAndFieldDirectiveResolvers[\get_class($pickerObjectTypeResolver)] = $pickerObjectTypeResolver;
        }
    }
    /**
     * @param array<string,mixed> $schemaDefinition
     */
    protected final function addInterfaceSchemaDefinitions(&$schemaDefinition) : void
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableUnionTypeImplementingInterfaceType()) {
            return;
        }
        // If it returns an interface as type, add it to the schemaDefinition
        $implementedInterfaceTypeResolvers = $this->unionTypeResolver->getUnionTypeInterfaceTypeResolvers();
        if ($implementedInterfaceTypeResolvers === []) {
            return;
        }
        $schemaDefinition[SchemaDefinition::INTERFACES] = [];
        foreach ($implementedInterfaceTypeResolvers as $implementedInterfaceTypeResolver) {
            $interfaceTypeName = $implementedInterfaceTypeResolver->getMaybeNamespacedTypeName();
            $interfaceTypeSchemaDefinition = [SchemaDefinition::TYPE_RESOLVER => $implementedInterfaceTypeResolver];
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($interfaceTypeSchemaDefinition);
            $schemaDefinition[SchemaDefinition::INTERFACES][$interfaceTypeName] = $interfaceTypeSchemaDefinition;
            $this->accessedTypeAndFieldDirectiveResolvers[\get_class($implementedInterfaceTypeResolver)] = $implementedInterfaceTypeResolver;
        }
    }
    /**
     * @param array<string,mixed> $schemaDefinition
     * @param bool $useGlobal
     */
    protected final function addDirectiveSchemaDefinitions(&$schemaDefinition, $useGlobal) : void
    {
        // Add the directives (non-global)
        $schemaFieldDirectiveResolvers = $this->unionTypeResolver->getSchemaFieldDirectiveResolvers(\false);
        if ($schemaFieldDirectiveResolvers === []) {
            return;
        }
        $schemaDefinition[SchemaDefinition::DIRECTIVES] = [];
        foreach ($schemaFieldDirectiveResolvers as $directiveName => $directiveResolver) {
            // Directives may not be directly visible in the schema
            if ($directiveResolver->skipExposingDirectiveInSchema($this->unionTypeResolver)) {
                continue;
            }
            $schemaDefinition[SchemaDefinition::DIRECTIVES][] = $directiveName;
            $this->accessedTypeAndFieldDirectiveResolvers[\get_class($directiveResolver)] = $directiveResolver;
            $this->accessedFieldDirectiveResolverClassRelationalTypeResolvers[\get_class($directiveResolver)] = $this->unionTypeResolver;
        }
    }
}

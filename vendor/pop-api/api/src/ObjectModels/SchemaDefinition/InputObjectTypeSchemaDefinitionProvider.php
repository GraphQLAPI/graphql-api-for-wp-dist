<?php

declare (strict_types=1);
namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPAPI\API\Schema\SchemaDefinition;
use PoPAPI\API\Schema\SchemaDefinitionHelpers;
use PoPAPI\API\Schema\TypeKinds;
class InputObjectTypeSchemaDefinitionProvider extends \PoPAPI\API\ObjectModels\SchemaDefinition\AbstractNamedTypeSchemaDefinitionProvider
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface
     */
    protected $inputObjectTypeResolver;
    public function __construct(InputObjectTypeResolverInterface $inputObjectTypeResolver)
    {
        $this->inputObjectTypeResolver = $inputObjectTypeResolver;
        parent::__construct($inputObjectTypeResolver);
    }
    public function getTypeKind() : string
    {
        return TypeKinds::INPUT_OBJECT;
    }
    /**
     * @return array<string,mixed>
     */
    public function getSchemaDefinition() : array
    {
        $schemaDefinition = parent::getSchemaDefinition();
        $this->addInputFieldSchemaDefinitions($schemaDefinition);
        return $schemaDefinition;
    }
    /**
     * @param array<string,mixed> $schemaDefinition
     */
    protected final function addInputFieldSchemaDefinitions(&$schemaDefinition) : void
    {
        $schemaDefinition[SchemaDefinition::INPUT_FIELDS] = [];
        $schemaInputObjectTypeFieldResolvers = $this->inputObjectTypeResolver->getConsolidatedInputFieldNameTypeResolvers();
        /** @var string $inputFieldName */
        foreach (\array_keys($schemaInputObjectTypeFieldResolvers) as $inputFieldName) {
            // Fields may not be directly visible in the schema
            if ($this->inputObjectTypeResolver->skipExposingInputFieldInSchema($inputFieldName)) {
                continue;
            }
            $inputFieldSchemaDefinition = $this->inputObjectTypeResolver->getInputFieldSchemaDefinition($inputFieldName);
            // Extract the typeResolvers
            /** @var TypeResolverInterface */
            $inputFieldTypeResolver = $inputFieldSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
            $this->accessedTypeAndFieldDirectiveResolvers[\get_class($inputFieldTypeResolver)] = $inputFieldTypeResolver;
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($inputFieldSchemaDefinition);
            $schemaDefinition[SchemaDefinition::INPUT_FIELDS][$inputFieldName] = $inputFieldSchemaDefinition;
        }
    }
}

<?php

declare (strict_types=1);
namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoPAPI\API\Schema\TypeKinds;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
class EnumTypeSchemaDefinitionProvider extends \PoPAPI\API\ObjectModels\SchemaDefinition\AbstractNamedTypeSchemaDefinitionProvider
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface
     */
    protected $enumTypeResolver;
    public function __construct(EnumTypeResolverInterface $enumTypeResolver)
    {
        $this->enumTypeResolver = $enumTypeResolver;
        parent::__construct($enumTypeResolver);
    }
    public function getTypeKind() : string
    {
        return TypeKinds::ENUM;
    }
    /**
     * @return array<string,mixed>
     */
    public function getSchemaDefinition() : array
    {
        $schemaDefinition = parent::getSchemaDefinition();
        $this->addEnumSchemaDefinition($schemaDefinition);
        return $schemaDefinition;
    }
    /**
     * Add the enum values in the schema: arrays of enum name,
     * description, deprecated and deprecation description
     * @param array<string,mixed> $schemaDefinition
     */
    protected final function addEnumSchemaDefinition(&$schemaDefinition) : void
    {
        $schemaDefinition[SchemaDefinition::ITEMS] = $this->enumTypeResolver->getEnumSchemaDefinition();
    }
}

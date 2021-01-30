<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject;
class EnumValue extends \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject
{
    public function getName() : string
    {
        return (string) $this->getValue();
    }
    public function getValue()
    {
        return $this->schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME];
    }
    public function getDescription() : ?string
    {
        return $this->schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION] ?? null;
    }
    public function isDeprecated() : bool
    {
        return $this->schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEPRECATED] ?? \false;
    }
    public function getDeprecatedReason() : ?string
    {
        return $this->schemaDefinition[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION] ?? null;
    }
}

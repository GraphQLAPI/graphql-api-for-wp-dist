<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;
class ScalarType extends \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractNamedType
{
    public function getKind() : string
    {
        return \GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds::SCALAR;
    }
    public function getSpecifiedByURL() : ?string
    {
        return $this->schemaDefinition[SchemaDefinition::SPECIFIED_BY_URL] ?? null;
    }
}

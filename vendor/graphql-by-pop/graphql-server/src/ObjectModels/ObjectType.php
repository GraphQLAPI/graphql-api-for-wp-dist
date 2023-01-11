<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

class ObjectType extends \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractNamedType implements \GraphQLByPoP\GraphQLServer\ObjectModels\HasFieldsTypeInterface, \GraphQLByPoP\GraphQLServer\ObjectModels\HasInterfacesTypeInterface
{
    use \GraphQLByPoP\GraphQLServer\ObjectModels\HasFieldsTypeTrait;
    use \GraphQLByPoP\GraphQLServer\ObjectModels\HasInterfacesTypeTrait;
    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);
        $this->initFields($fullSchemaDefinition, $schemaDefinitionPath);
    }
    public function getKind() : string
    {
        return \GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds::OBJECT;
    }
}

<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType;
abstract class AbstractNestableType extends \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType
     */
    protected $nestedType;
    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath, \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType $nestedType, array $customDefinition = [])
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath, $customDefinition);
        $this->nestedType = $nestedType;
    }
    public function getNestedType() : \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType
    {
        return $this->nestedType;
    }
    public function getNestedTypeID() : string
    {
        return $this->nestedType->getID();
    }
}

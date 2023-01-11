<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface NamedTypeInterface extends \GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface, \GraphQLByPoP\GraphQLServer\ObjectModels\SchemaDefinitionReferenceObjectInterface
{
    public function getNamespacedName() : string;
    public function getElementName() : string;
    public function getExtensions() : \GraphQLByPoP\GraphQLServer\ObjectModels\NamedTypeExtensions;
}

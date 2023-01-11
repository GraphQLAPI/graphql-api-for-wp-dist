<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

class UnionType extends \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractNamedType implements \GraphQLByPoP\GraphQLServer\ObjectModels\HasPossibleTypesTypeInterface
{
    use \GraphQLByPoP\GraphQLServer\ObjectModels\HasPossibleTypesTypeTrait;
    public function getKind() : string
    {
        return \GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds::UNION;
    }
}

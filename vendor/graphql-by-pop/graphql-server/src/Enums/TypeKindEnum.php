<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;
use GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds;
class TypeKindEnum extends \PoP\ComponentModel\Enums\AbstractEnum
{
    public const NAME = 'TypeKind';
    protected function getEnumName() : string
    {
        return self::NAME;
    }
    public function getValues() : array
    {
        return [\GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds::SCALAR, \GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds::OBJECT, \GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds::INTERFACE, \GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds::UNION, \GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds::ENUM, \GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds::INPUT_OBJECT, \GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds::LIST, \GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds::NON_NULL];
    }
}

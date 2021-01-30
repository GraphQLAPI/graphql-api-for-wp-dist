<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;
use GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations;
class DirectiveLocationEnum extends \PoP\ComponentModel\Enums\AbstractEnum
{
    public const NAME = 'DirectiveLocation';
    protected function getEnumName() : string
    {
        return self::NAME;
    }
    public function getValues() : array
    {
        return [\GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::QUERY, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::MUTATION, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::SUBSCRIPTION, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::FIELD, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::FRAGMENT_DEFINITION, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::FRAGMENT_SPREAD, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::INLINE_FRAGMENT, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::SCHEMA, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::SCALAR, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::OBJECT, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::FIELD_DEFINITION, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::ARGUMENT_DEFINITION, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::INTERFACE, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::UNION, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::ENUM, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::ENUM_VALUE, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::INPUT_OBJECT, \GraphQLByPoP\GraphQLServer\ObjectModels\DirectiveLocations::INPUT_FIELD_DEFINITION];
    }
}

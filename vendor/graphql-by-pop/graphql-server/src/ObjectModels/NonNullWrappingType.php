<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaHelpers;
class NonNullWrappingType extends \GraphQLByPoP\GraphQLServer\ObjectModels\AbstractWrappingType
{
    public function getName() : string
    {
        return GraphQLSchemaHelpers::getNonNullTypeName($this->wrappedType->getName());
    }
    public function getID() : string
    {
        return GraphQLSchemaHelpers::getNonNullTypeName($this->wrappedType->getID());
    }
    public function getKind() : string
    {
        return \GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds::NON_NULL;
    }
}

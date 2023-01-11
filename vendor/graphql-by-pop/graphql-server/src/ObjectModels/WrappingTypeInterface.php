<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface WrappingTypeInterface extends \GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface
{
    public function getWrappedType() : \GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface;
    public function getWrappedTypeID() : string;
}

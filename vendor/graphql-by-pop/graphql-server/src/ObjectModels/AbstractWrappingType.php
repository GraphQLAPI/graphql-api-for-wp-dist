<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

abstract class AbstractWrappingType implements \GraphQLByPoP\GraphQLServer\ObjectModels\WrappingTypeInterface
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface
     */
    protected $wrappedType;
    public function __construct(\GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface $wrappedType)
    {
        $this->wrappedType = $wrappedType;
    }
    public function getWrappedType() : \GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface
    {
        return $this->wrappedType;
    }
    public function getWrappedTypeID() : string
    {
        return $this->wrappedType->getID();
    }
    public function getDescription() : ?string
    {
        return null;
    }
}

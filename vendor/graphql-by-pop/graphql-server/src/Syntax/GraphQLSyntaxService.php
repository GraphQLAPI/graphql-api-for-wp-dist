<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Syntax;

class GraphQLSyntaxService implements \GraphQLByPoP\GraphQLServer\Syntax\GraphQLSyntaxServiceInterface
{
    /**
     * Indicate if the type if of type "LIST"
     * @param string $typeNameOrID
     */
    public function isListWrappingType($typeNameOrID) : bool
    {
        return \substr($typeNameOrID, 0, 1) == '[' && \substr($typeNameOrID, -1) == ']';
    }
    /**
     * Extract the nested types inside the list
     * @param string $typeNameOrID
     */
    public function extractWrappedTypeFromListWrappingType($typeNameOrID) : string
    {
        return \substr($typeNameOrID, 1, \strlen($typeNameOrID) - 2);
    }
    /**
     * Indicate if the type if of type "NON_NULL"
     * @param string $typeNameOrID
     */
    public function isNonNullWrappingType($typeNameOrID) : bool
    {
        return \substr($typeNameOrID, -1) == '!';
    }
    /**
     * Extract the nested types which are "non null"
     * @param string $typeNameOrID
     */
    public function extractWrappedTypeFromNonNullWrappingType($typeNameOrID) : string
    {
        return \substr($typeNameOrID, 0, \strlen($typeNameOrID) - 1);
    }
}

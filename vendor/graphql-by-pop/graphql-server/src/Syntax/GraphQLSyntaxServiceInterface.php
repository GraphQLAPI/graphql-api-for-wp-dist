<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Syntax;

interface GraphQLSyntaxServiceInterface
{
    /**
     * Indicate if the type if of type "LIST"
     * @param string $typeNameOrID
     */
    public function isListWrappingType($typeNameOrID) : bool;
    /**
     * Extract the nested types inside the list
     * @param string $typeNameOrID
     */
    public function extractWrappedTypeFromListWrappingType($typeNameOrID) : string;
    /**
     * Indicate if the type if of type "NON_NULL"
     * @param string $typeNameOrID
     */
    public function isNonNullWrappingType($typeNameOrID) : bool;
    /**
     * Extract the nested types which are "non null"
     * @param string $typeNameOrID
     */
    public function extractWrappedTypeFromNonNullWrappingType($typeNameOrID) : string;
}

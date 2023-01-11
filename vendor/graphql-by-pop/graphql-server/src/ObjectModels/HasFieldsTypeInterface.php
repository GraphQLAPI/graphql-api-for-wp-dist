<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface HasFieldsTypeInterface extends \GraphQLByPoP\GraphQLServer\ObjectModels\NamedTypeInterface
{
    /**
     * @param bool $includeGlobal Custom parameter by this GraphQL Server (i.e. it is not present in the GraphQL spec)
     * @return Field[]
     * @param bool $includeDeprecated
     */
    public function getFields($includeDeprecated = \false, $includeGlobal = \true) : array;
    /**
     * @param bool $includeGlobal Custom parameter by this GraphQL Server (i.e. it is not present in the GraphQL spec)
     * @return string[]
     * @param bool $includeDeprecated
     */
    public function getFieldIDs($includeDeprecated = \false, $includeGlobal = \true) : array;
}

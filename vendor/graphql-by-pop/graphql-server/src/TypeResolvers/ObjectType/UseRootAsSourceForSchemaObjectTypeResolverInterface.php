<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
interface UseRootAsSourceForSchemaObjectTypeResolverInterface
{
    /**
     * @param \PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface $objectTypeFieldResolver
     * @param string $fieldName
     */
    public function isFieldNameConditionSatisfiedForSchema($objectTypeFieldResolver, $fieldName) : bool;
}
